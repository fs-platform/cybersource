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
     * @return \CyberSource\Model\GenerateCaptureContextRequest
     */
    public function buildData(array $config) : GenerateCaptureContextRequest
    {
        $data = [
            'targetOrigins'       => $config['target_origins'],
            'clientVersion'       => $config['client_version'],
            'allowedCardNetworks' => $config['allowed_card_networks']
        ];

        Log::channel(config('cybersource.channel') ?: 'cybersource')
            ->info('cybersource client jwt初始化 请求参数',$data);

        return new GenerateCaptureContextRequest($data);
    }

    /**
     * 初始化jwk
     * @throws \Exception
     */
    public function jwt(array $config) : string
    {
        $integrationApi = new MicroformIntegrationApi($this->client($config));

        $response = $integrationApi->generateCaptureContext($this->buildData($config));

        if (empty($response) || !is_array($response) || count($response) == 0) {
            Log::channel(config('cybersource.channel') ?: 'cybersource')
                ->emergency('cybersource client jwt初始化异常 response响应数据异常:'. json_encode($response));

            return "";
        }

        return current($response);
    }
}