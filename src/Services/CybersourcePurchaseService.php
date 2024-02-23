<?php

namespace Smbear\Cybersource\Services;

use CyberSource\Api\CaptureApi;
use CyberSource\Api\PaymentsApi;
use CyberSource\ApiException;
use CyberSource\Model\CapturePaymentRequest;
use CyberSource\Model\CreatePaymentRequest;
use CyberSource\Model\Ptsv2paymentsClientReferenceInformation;
use CyberSource\Model\Ptsv2paymentsDeviceInformation;
use CyberSource\Model\Ptsv2paymentsidcapturesOrderInformation;
use CyberSource\Model\Ptsv2paymentsidcapturesOrderInformationAmountDetails;
use CyberSource\Model\Ptsv2paymentsidcapturesOrderInformationShipTo;
use CyberSource\Model\Ptsv2paymentsOrderInformation;
use CyberSource\Model\Ptsv2paymentsOrderInformationAmountDetails;
use CyberSource\Model\Ptsv2paymentsOrderInformationBillTo;
use CyberSource\Model\PtsV2PaymentsPost201Response;
use CyberSource\Model\Ptsv2paymentsProcessingInformation;
use CyberSource\Model\Ptsv2paymentsTokenInformation;
use Illuminate\Support\Facades\Log;
use Smbear\Cybersource\Exceptions\CybersourceBaseException;
use Smbear\Cybersource\Traits\CybersourceClient;

class CybersourcePurchaseService
{
    use CybersourceClient;

    /**
     * AUTHORIZED SUCCESS
     */
    const AUTHORIZED = "AUTHORIZED";

    /**
     * SUCCESS
     */
    const SUCCESS = "SUCCESS";

    /**
     * ERROR
     */
    const ERROR = "ERROR";

    /**
     * 成功状态
     */
    const SUCCESS_CODE = 201;

    /**
     * 构建支付请求数据
     * @param array $params
     * @param int $orderId
     * @return array
     */
    public function buildPaymentRequestParams(array $params,int $orderId) : array
    {
        Log::channel(config('cybersource.channel') ?: 'cybersource')
            ->info('订单id:'.$orderId.' cybersource client 支付 请求参数',$params);

        $orderInformation = new Ptsv2paymentsOrderInformation([
            'amountDetails'         => new Ptsv2paymentsOrderInformationAmountDetails($params['amount'] ?? []),
            'billTo'                => new Ptsv2paymentsOrderInformationBillTo($params['billing'] ?? []),
            'shipTo'                => new Ptsv2paymentsidcapturesOrderInformationShipTo($params['shipping'] ?? [])
        ]);

        $clientReferenceInformation = new Ptsv2paymentsClientReferenceInformation([
            'code' =>  $params['reference'] ?? ''
        ]);

        $tokenInformation = new Ptsv2paymentsTokenInformation([
            'transientTokenJwt' => $params['token'] ?? ''
        ]);

        $processingInformation = new Ptsv2paymentsProcessingInformation([
           'capture' => true
        ]);

        $deviceInformation = new Ptsv2paymentsDeviceInformation([
            'ipAddress' => $params['ipAddress'] ?? ''
        ]);

        return [
            'clientReferenceInformation' => $clientReferenceInformation,
            'orderInformation'           => $orderInformation,
            'tokenInformation'           => $tokenInformation,
            'processingInformation'      => $processingInformation,
            'deviceInformation'          => $deviceInformation
        ];
    }

    /**
     * 构建捕获请求数据
     * @param array $params
     * @return array
     */
    public function buildCaptureRequestParams(array $params) : array
    {
        $clientReferenceInformation = new Ptsv2paymentsClientReferenceInformation([
            'code' =>  $params['reference'] ?? ''
        ]);

        $orderInformation = new Ptsv2paymentsidcapturesOrderInformation([
            'amountDetails' => new Ptsv2paymentsidcapturesOrderInformationAmountDetails($params['amount'])
        ]);

        return [
            'clientReferenceInformation' => $clientReferenceInformation,
            'orderInformation'           => $orderInformation
        ];
    }

    /**
     * 订单支付
     * @param array $config
     * @param array $params
     * @param string $local
     * @param int $orderId
     * @return array
     * @throws ApiException
     * @throws \CyberSource\Authentication\Core\AuthException
     */
    public function purchase(array $config,array $params,string $locale,int $orderId) : array
    {
        $request = new CreatePaymentRequest($this->buildPaymentRequestParams($params,$orderId));

        $paymentsApi = new PaymentsApi($this->client($config));
        $response = $paymentsApi->createPayment($request);

        //记录请求数据
        Log::channel(config('cybersource.channel') ?: 'cybersource')
            ->info('订单id:'.$orderId.' cybersource client 支付 响应参数',$response);

        if (!empty($response) && is_array($response)) {
            $responseData = $response[0] ?? null;

            $responseStatusId = $response[1] ?? 500;
            $status = $responseData->getStatus() ?? '';

            $data = [
                'code' => $responseStatusId,
                'status' => $status,
            ];

            if (!empty($responseData) && $responseData instanceof PtsV2PaymentsPost201Response) {
                $data['id'] = $responseData->getId();

                $errorInformation = $responseData->getErrorInformation();

                if (!empty($errorInformation)) {
                    $data['message'] = $errorInformation->getMessage();
                }
                
                if ($responseStatusId == self::SUCCESS_CODE && strtoupper($status) == self::AUTHORIZED) {
                    $data['reason'] = self::SUCCESS;
                    $data['message'] = self::SUCCESS;
                    $data['action'] = self::SUCCESS;

                    return cybersource_return_success("success",$data);
                }
            }
            
            $reason = $responseData->getErrorInformation()->getReason() ?? '';

            if (!empty($reason)) {
                $data['reason'] = $reason;

                $errorDescribeData = cybersource_error_message($locale,'Payment',$responseStatusId,$status,$reason);

                if (!empty($errorDescribeData)) {
                    $data['message'] = $errorDescribeData['message'];
                    $data['action'] = $errorDescribeData['action'];

                    return cybersource_return_error("error",$data);
                }
            }
        }

        return cybersource_return_error('error',$data);
    }
}