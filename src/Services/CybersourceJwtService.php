<?php

namespace Smbear\Cybersource\Services;

use CyberSource\Api\MicroformIntegrationApi;
use CyberSource\Model\GenerateCaptureContextRequest;
use Illuminate\Support\Facades\Log;
use Smbear\Cybersource\Exceptions\CybersourceBaseException;
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

        return new GenerateCaptureContextRequest($data);
    }

    /**
     * 初始化jwk
     * @throws \Exception
     */
    public function jwt(array $config) : string
    {
        try {
            $integrationApi = new MicroformIntegrationApi($this->client($config));

            $buildParams = $this->buildData($config);

            Log::channel(config('cybersource.channel') ?: 'cybersource')
                ->info('jwt 初始化 client请求参数',$buildParams);

            $response = $integrationApi->generateCaptureContext($buildParams);

            if (empty($response) || !is_array($response) || count($response) == 0) {
                Log::channel(config('cybersource.channel') ?: 'cybersource')
                    ->emergency('jwt 初始化异常 response 数据异常:'. json_encode($response));

                return "";
            }

            return current($response);
        } catch (\Exception $exception) {
            Log::channel(config('cybersource.channel') ?: 'cybersource')
                ->emergency('jwt 初始化异常 response 数据异常:'. $exception->getMessage());

            report($exception);

            throw new CybersourceBaseException($exception->getMessage());
        }
    }
}