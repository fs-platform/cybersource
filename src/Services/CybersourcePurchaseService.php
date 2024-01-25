<?php

namespace Smbear\Cybersource\Services;

use CyberSource\Api\CaptureApi;
use CyberSource\Api\PaymentsApi;
use CyberSource\Model\CapturePaymentRequest;
use CyberSource\Model\CreatePaymentRequest;
use CyberSource\Model\Ptsv2paymentsClientReferenceInformation;
use CyberSource\Model\Ptsv2paymentsidcapturesOrderInformation;
use CyberSource\Model\Ptsv2paymentsidcapturesOrderInformationAmountDetails;
use CyberSource\Model\Ptsv2paymentsidcapturesOrderInformationShipTo;
use CyberSource\Model\Ptsv2paymentsOrderInformation;
use CyberSource\Model\Ptsv2paymentsOrderInformationAmountDetails;
use CyberSource\Model\Ptsv2paymentsOrderInformationBillTo;
use CyberSource\Model\PtsV2PaymentsPost201Response;
use CyberSource\Model\Ptsv2paymentsTokenInformation;
use CyberSource\Model\RefreshPaymentStatusRequest;
use Smbear\Cybersource\Traits\CybersourceClient;


class CybersourcePurchaseService
{
    use CybersourceClient;

    /**
     * 构建支付请求数据
     * @param array $params
     * @return array
     */
    public function buildPaymentRequestParams(array $params) : array
    {
        $orderInformation = new Ptsv2paymentsOrderInformation([
            'amountDetails' => new Ptsv2paymentsOrderInformationAmountDetails($params['amount'] ?? []),
            'billTo'        => new Ptsv2paymentsOrderInformationBillTo($params['billing'] ?? []),
            'shipTo'        => new Ptsv2paymentsidcapturesOrderInformationShipTo($params['shipping'] ?? [])
        ]);

        $clientReferenceInformation = new Ptsv2paymentsClientReferenceInformation([
            'code' =>  $params['reference'] ?? ''
        ]);

        $tokenInformation = new Ptsv2paymentsTokenInformation([
            'transientTokenJwt' => $params['token'] ?? ''
        ]);

        return [
            'clientReferenceInformation' => $clientReferenceInformation,
            'orderInformation'           => $orderInformation,
            'tokenInformation'           => $tokenInformation
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
    
    public function buildStatusRequestParams(array $params) : array 
    {
        $clientReferenceInformation = new Ptsv2paymentsClientReferenceInformation([
            'code' =>  $params['reference'] ?? ''
        ]);

        return [
            'clientReferenceInformation' => $clientReferenceInformation,
        ];
    }

    /**
     * 订单支付
     * @throws \CyberSource\Authentication\Core\AuthException
     */
    public function purchase(array $config,array $params)
    {

        $request = new CreatePaymentRequest($this->buildPaymentRequestParams($params));

        $paymentsApi = new PaymentsApi($this->client($config));

        try {
            $response = $paymentsApi->createPayment($request);

            if (!empty($response) && is_array($response)) {
                $response = current($response);

                $status = $response->getStatus();

                if ($status == "AUTHORIZED_PENDING_REVIEW") {
                    $id = $response->getId();

                    $this->capture($config,$params,$id);
                }
            }

//            $this->capture($config,$params,"a123");

            //7060815035566020904953

            dd($response);

        } catch (\Exception $exception) {
            dd($exception);
        }
    }

    public function get(array $config,array $params,string $id) {
        $request = new RefreshPaymentStatusRequest($this->buildStatusRequestParams($params));

        $paymentsApi = new PaymentsApi($this->client($config));

        try {
            $response = $paymentsApi->refreshPaymentStatus($id, $request);
        } catch (\Exception $exception) {
            dd($exception);
        }


        dd($response);
    }

    /**
     * @throws \CyberSource\Authentication\Core\AuthException
     */
    public function capture(array $config, array $params, string $id)
    {
        $request = new CapturePaymentRequest($this->buildCaptureRequestParams($params));

        $captureApi = new CaptureApi($this->client($config));

        try {
            $response = $captureApi->capturePayment($request, $id);

            dump($response);
        } catch (\Exception $exception) {
            dd($exception);
        }
    }
}