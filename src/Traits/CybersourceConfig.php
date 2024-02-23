<?php

namespace Smbear\Cybersource\Traits;

use Smbear\Cybersource\Enums\CybersourceEnums;
use Smbear\Cybersource\Exceptions\CybersourceConfigExceptionCybersource;

trait CybersourceConfig
{
    /**
     * @var array 模型下的配置文件
     */
    public $config;

    /**
     * @var string 语种
     */
    public $locale = 'en';

    /**
     * @var string 是生产模型还是沙盒模型
     */
    public $environment = 'sandbox';

    /**
     * 设置当前语种
     * @param string $locale
     */
    public function setLocale(string $locale = '')
    {
        $this->locale = $locale ?: 'en';
    }

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
     * @param string $type
     * @return array 配置文件
     * @throws CybersourceConfigExceptionCybersource
     * @Author: smile
     * @Date: 2021/6/30
     * @Time: 17:58
     */
    public function getConfig(array $dependencies,string $type) : array
    {
        if (empty($this->config)) {
            $environment = $this->environment;

            array_walk($dependencies,function ($item) use ($environment,$type) {
                if (empty(config(CybersourceEnums::CYBERSOURCE.'.'.$environment.'.'.$type.'.'.$item))){
                    throw new CybersourceConfigExceptionCybersource(CybersourceEnums::CYBERSOURCE. $environment .'.'.$item.' 参数为空');
                }
            });

            $this->config = config('cybersource.' . $environment.'.'.$type);
        }

        return $this->config;
    }
}