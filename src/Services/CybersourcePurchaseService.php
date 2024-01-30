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
use CyberSource\Model\Ptsv2paymentsTokenInformation;
use Illuminate\Support\Facades\Log;
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
        Log::channel(config('cybersource.channel') ?: 'cybersource')
            ->info('cybersource client 支付 请求参数',$params);

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

    /**
     * 订单支付
     * @param array $config
     * @param array $params
     * @return bool
     * @throws \CyberSource\Authentication\Core\AuthException
     * @throws \CyberSource\ApiException
     */
    public function purchase(array $config,array $params) : bool
    {
        $request = new CreatePaymentRequest($this->buildPaymentRequestParams($params));

        $paymentsApi = new PaymentsApi($this->client($config));

        $response = $paymentsApi->createPayment($request);

        if (!empty($response) && is_array($response)) {
            $response = current($response);

            $status = $response->getStatus();

            if ($status == "AUTHORIZED_PENDING_REVIEW") {
                $id = $response->getId();

                return $this->capture($config,$params,$id);
            }
        }

        return true;
    }

    /**
     * 捕获订单
     * @param array $config
     * @param array $params
     * @param string $id
     * @return bool
     * @throws \CyberSource\Authentication\Core\AuthException
     * @throws \CyberSource\ApiException
     */
    public function capture(array $config, array $params, string $id): bool
    {
        $request = new CapturePaymentRequest($this->buildCaptureRequestParams($params));

        $captureApi = new CaptureApi($this->client($config));

        $response = $captureApi->capturePayment($request, $id);

        if (!empty($response) && is_array($response)) {
            $response = current($response);

            $status = $response->getStatus();

            if ($status == "PENDING") {
                return true;
            }
        }

        return false;
    }
}