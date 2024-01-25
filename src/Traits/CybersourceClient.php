<?php

namespace Smbear\Cybersource\Traits;

use CyberSource\ApiClient;
use CyberSource\Authentication\Core\MerchantConfiguration;
use CyberSource\Configuration;

trait CybersourceClient
{
    /**
     * 获取客户端
     * @param array $config 配置文件
     * @return \CyberSource\ApiClient
     * @throws \CyberSource\Authentication\Core\AuthException
     */
    public function client(array $config) : ApiClient
    {
        $connectionConfiguration = $this->connectionConfiguration($config);
        $merchantConfiguration = $this->merchantConfiguration($config);

        return new ApiClient($connectionConfiguration, $merchantConfiguration);
    }

    /**
     * 配置商户配置
     * @param array $config
     * @return MerchantConfiguration
     * @throws \CyberSource\Authentication\Core\AuthException
     * @throws \Exception
     */
    public function merchantConfiguration(array $config): MerchantConfiguration
    {
        $merchantConfiguration = new MerchantConfiguration();

        $merchantConfiguration->setAuthenticationType(trim($config['authentication_type']));
        $merchantConfiguration->setMerchantID(trim($config['merchant_id']));
        $merchantConfiguration->setApiKeyID(trim($config['key']));
        $merchantConfiguration->setSecretKey(trim($config['secret']));
        $merchantConfiguration->setRunEnvironment(trim($config['point']));

        //验证商户数据
        $merchantConfiguration->validateMerchantData();

        return $merchantConfiguration;
    }

    /**
     * 配置连接配置
     * @param array $config
     * @return Configuration
     */
    public function connectionConfiguration(array $config): Configuration
    {
        $connectionConfiguration = new Configuration();

        $connectionConfiguration->setHost(trim($config['point']));

        return $connectionConfiguration;
    }
}