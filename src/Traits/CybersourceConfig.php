<?php

namespace Smbear\Cybersource\Traits;

use Smbear\Cybersource\Enums\CybersourceEnums;
use Smbear\Cybersource\Exceptions\CybersourceConfigException;

trait CybersourceConfig
{
    /**
     * @var array 模型下的配置文件
     */
    public $config;

    /**
     * @var string 是生产模型还是沙盒模型
     */
    public $environment = 'sandbox';

    /**
     * @Notes:设置 environment
     *
     * @param string $environment
     * @Author: smile
     * @Date: 2021/6/8
     * @Time: 18:53
     */
    public function setEnvironment(string $environment = '')
    {
        $this->environment = $environment ?: config('cybersource.environment');
    }

    /**
     * @Notes:获取到指定模型的配置数据
     *
     * @param array $dependencies
     * @return array 配置文件
     * @throws CybersourceConfigException
     * @Author: smile
     * @Date: 2021/6/30
     * @Time: 17:58
     */
    public function getConfig(array $dependencies) : array
    {
        if (empty($this->config)) {
            $environment = $this->environment;

            array_walk($dependencies,function ($item) use ($environment) {
                if (empty(config(CybersourceEnums::CYBERSOURCE.'.'.$environment.'.'.$item))){
                    throw new CybersourceConfigException(CybersourceEnums::CYBERSOURCE. $environment .'.'.$item.' 参数为空');
                }
            });

            $this->config = config('cybersource.' . $environment);
        }

        return $this->config;
    }
}