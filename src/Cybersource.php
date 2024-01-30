<?php

namespace Smbear\Cybersource;

use CyberSource\ApiException;
use Illuminate\Support\Facades\Log;
use Smbear\Cybersource\Exceptions\CybersourceBaseException;
use Smbear\Cybersource\Exceptions\CybersourceConfigExceptionCybersource;
use Smbear\Cybersource\Exceptions\CybersourceParamsExceptionCybersource;
use Smbear\Cybersource\Services\CybersourceJwtService;
use Smbear\Cybersource\Services\CybersourcePurchaseService;
use Smbear\Cybersource\Traits\CybersourceConfig;

class Cybersource
{
    use CybersourceConfig;

    /**
     * @var string token
     */
    private $token;

    /**
     * @var array 账单地址
     */
    private $billing;

    /**
     * @var array 金额
     */
    private $amount;

    /**
     * @var array 运输地址
     */
    private $shipping;

    /**
     * @var string 交易单号
     */
    private $reference;

    /**
     * @var CybersourceJwtService jwtService
     */
    private $jwtService;

    /**
     * 方法参数异常描述
     */
    const PARAMS_EXCEPTION = ": Method parameter exception";

    /**
     * @var CybersourcePurchaseService purchaseService
     */
    private $purchaseService;

    public function __construct()
    {
        $this->setEnvironment();

        $this->jwtService = new CybersourceJwtService();

        $this->purchaseService = new CybersourcePurchaseService();
    }

    /**
     * 初始化jwt
     * @return array
     * @throws \Exception
     */
    public function initJwt(): array
    {
        try {
            $config = $this->getConfig([
                'point',
                'merchant_id',
                'encryption_type',
                'key',
                'secret',
                'authentication_type',
                'target_origins'
            ]);

            $jwt = $this->jwtService->jwt($config);

            if (empty($jwt)) {
                return cybersource_return_error("exception",[]);
            }

            return cybersource_return_success("success",[
                'jwt' => $jwt
            ]);
        }catch (\Exception $exception) {
            if ($exception instanceof CybersourceBaseException) {
                Log::channel(config('cybersource.channel') ?: 'cybersource')
                    ->emergency('cybersource client jwt初始化异常 异常原因:' . $exception->getMessage());
            }  elseif ($exception instanceof ApiException) {
                $responseBody = $exception->getResponseBody();

                if (!empty($responseBody) && is_object($responseBody)) {
                    $context = get_object_vars($responseBody);

                    Log::channel(config('cybersource.channel') ?: 'cybersource')
                        ->emergency('cybersource client jwt初始化异常 response 数据异常:'. $exception->getMessage(),$context);
                }
            }

            report($exception);

            throw $exception;
        }
    }

    /**
     * 订单支付
     * @return array
     * @throws \Exception
     */
    public function purchase() : array
    {
        try {
            $this->checkMethods([
                [
                    'method' => 'setToken',
                    'attribute' => 'token'
                ],
                [
                    'method' => 'setAmount',
                    'attribute' => 'amount'
                ],
                [
                    'method' => 'setReference',
                    'attribute' => 'reference'
                ],
                [
                    'method' => 'setBilling',
                    'attribute' => 'billing'
                ],
                [
                    'method' => 'setShipping',
                    'attribute' => 'shipping'
                ],
            ]);

            $config = $this->getConfig([
                'point',
                'merchant_id',
                'encryption_type',
                'key',
                'secret'
            ]);

            $params = [
                'token'     => $this->token,
                'reference' => $this->reference,
                'amount'    => $this->amount,
                'billing'   => $this->billing,
                'shipping'  => $this->shipping
            ];

            $data = $this->purchaseService->purchase($config,$params);

            if ($data) {
                return cybersource_return_success("success",[
                    'result' => true
                ]);
            }

            return cybersource_return_error("error",[
                'result' => false
            ]);
        }catch (\Exception $exception) {
            if ($exception instanceof CybersourceBaseException) {
                Log::channel(config('cybersource.channel') ?: 'cybersource')
                    ->emergency('cybersource client 支付异常 异常原因:' . $exception->getMessage());
            }  elseif ($exception instanceof ApiException) {
                $responseBody = $exception->getResponseBody();

                if (!empty($responseBody) && is_object($responseBody)) {
                    $context = get_object_vars($responseBody);

                    Log::channel(config('cybersource.channel') ?: 'cybersource')
                        ->emergency('cybersource client 支付异常 response 数据异常:'. $exception->getMessage(),$context);
                }
            }

            report($exception);

            throw $exception;
        }
    }

    /**
     * 设置数据token
     * @throws \Smbear\Cybersource\Exceptions\CybersourceParamsExceptionCybersource
     */
    public function setToken(string $token) : Cybersource
    {
        if (empty($token)) {
            throw new CybersourceParamsExceptionCybersource(__FUNCTION__.self::PARAMS_EXCEPTION);
        }

        $this->token = $token;

        return $this;
    }

    /**
     * 设置订单编号
     * @param string $reference
     * @return $this
     * @throws \Smbear\Cybersource\Exceptions\CybersourceParamsExceptionCybersource
     */
    public function setReference(string $reference): Cybersource
    {
        if (empty($reference)) {
            throw new CybersourceParamsExceptionCybersource(__FUNCTION__.self::PARAMS_EXCEPTION);
        }

        $this->reference = $reference;

        return $this;
    }

    /**
     * 设置订单金额
     * @param array $amount
     * @return \Smbear\Cybersource\Cybersource
     * @throws \Smbear\Cybersource\Exceptions\CybersourceParamsExceptionCybersource
     */
    public function setAmount(array $amount): Cybersource
    {
        if (empty($amount)) {
            throw new CybersourceParamsExceptionCybersource(__FUNCTION__.self::PARAMS_EXCEPTION);
        }

        //判断订单金额
        $amountValue = intval($amount['amount'] ?? 0);

        if ($amountValue <= 0) {
            throw new CybersourceParamsExceptionCybersource(__FUNCTION__.self::PARAMS_EXCEPTION);
        }
        
        $this->amount = [
            'totalAmount' => $amount['amount'],
            'currency'    => $amount['currency']
        ];

        return $this;
    }

    /**
     * 设置账单地址
     * @param array $billing
     * @return $this
     * @throws \Smbear\Cybersource\Exceptions\CybersourceParamsExceptionCybersource
     */
    public function setBilling(array $billing) : Cybersource
    {
        if (empty($billing)) {
            throw new CybersourceParamsExceptionCybersource(__FUNCTION__.self::PARAMS_EXCEPTION);
        }

        $this->billing = [
            "firstName"          => trim($billing['first_name']),
            "lastName"           => trim($billing['last_name']),
            "address1"           => trim($billing['address1']),
            "locality"           => trim($billing['locality']),
            "administrativeArea" => trim($billing['administrative_area']),
            "postalCode"         => trim($billing['postal_code']),
            "country"            => trim($billing['country']),
            "district"           => trim($billing['district']),
            "buildingNumber"     => trim($billing['building_number']),
            "email"              => trim($billing['email']),
            "phoneNumber"        => trim($billing['phone_number'])
        ];

        return $this;
    }

    /**
     * 设置运输地址
     * @param array $shipping
     * @return $this
     * @throws \Smbear\Cybersource\Exceptions\CybersourceParamsExceptionCybersource
     */
    public function setShipping(array $shipping) : Cybersource
    {
        if (empty($shipping)) {
            throw new CybersourceParamsExceptionCybersource(__FUNCTION__.self::PARAMS_EXCEPTION);
        }

        $this->shipping = [
            "firstName"          => trim($shipping['first_name']),
            "lastName"           => trim($shipping['last_name']),
            "address1"           => trim($shipping['address1']),
            "locality"           => trim($shipping['locality']),
            "administrativeArea" => trim($shipping['administrative_area']),
            "postalCode"         => trim($shipping['postal_code']),
            "country"            => trim($shipping['country']),
            "district"           => trim($shipping['district']),
            "buildingNumber"     => trim($shipping['building_number']),
            "email"              => trim($shipping['email']),
            "phoneNumber"        => trim($shipping['phone_number'])
        ];

        return $this;
    }

    /**
     * 核实方法是否被调用
     * @param array $methods
     * @throws \Smbear\Cybersource\Exceptions\CybersourceConfigExceptionCybersource
     */
    public function checkMethods($methods = [])
    {
        array_walk($methods,function ($item){
            $attribute = $item['attribute'] ?? '';

            if (empty($attribute)) {
                return ;
            }

            if (!isset($this->$attribute)  || empty($this->$attribute)) {
                throw new CybersourceConfigExceptionCybersource($item['method'].' 异常，需要call function');
            }
        });
    }

}