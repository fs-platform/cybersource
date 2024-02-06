<?php

namespace Smbear\Cybersource\Services;

use CyberSource\Api\MicroformIntegrationApi;
use CyberSource\Model\GenerateCaptureContextRequest;
use Illuminate\Support\Facades\Log;
use Smbear\Cybersource\Traits\CybersourceClient;

class CybersourceJwtService {
    use CybersourceClient;

    /**
     * @var array $config 配置文件
     */
    public $config;

    /**
     * 建造jwt初始化所需数据
     * @param array $config
     * @param int $orderId
     * @return GenerateCaptureContextRequest
     */
    public function buildData(array $config,string $orderId) : GenerateCaptureContextRequest
    {
        $data = [
            'targetOrigins'       => $config['target_origins'],
            'clientVersion'       => $config['client_version'],
            'allowedCardNetworks' => $config['allowed_card_networks']
        ];

        Log::channel(config('cybersource.channel') ?: 'cybersource')
            ->info('订单id:'.($orderId?? '').' cybersource client jwt初始化 请求参数:',$data);

        return new GenerateCaptureContextRequest($data);
    }

    /**
     * 初始化jwk
     * @param array $config
     * @param int $orderId
     * @return array
     * @throws \CyberSource\ApiException
     * @throws \CyberSource\Authentication\Core\AuthException
     */
    public function jwt(array $config,string $orderId) : array
    {
        $integrationApi = new MicroformIntegrationApi($this->client($config));

        $response = $integrationApi->generateCaptureContext($this->buildData($config,$orderId));

        Log::channel(config('cybersource.channel') ?: 'cybersource')
            ->info('订单id:'.($orderId?? '').' cybersource client jwt初始化 响应数据:',$response);

        if (empty($response) || !is_array($response) || count($response) == 0) {


            return cybersource_return_error("error",[]);
        }

        return cybersource_return_success("success",[
            'jwt' => current($response)
        ]);
    }
}