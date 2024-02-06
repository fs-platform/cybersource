<?php

namespace Smbear\Cybersource\Exceptions;

use CyberSource\ApiException;
use \Exception;
use Illuminate\Support\Facades\Log;

class CybersourceBaseException extends Exception
{
    public static function exceptionHadle(\Exception $exception,string $locale,int $orderId,string $type) : array
    {
        $data = [
            'message' => $exception->getMessage(),
            'code'    => $exception->getCode()
        ];
        
        if ($exception instanceof ApiException) {
            $responseBody = $exception->getResponseBody();

            if (!empty($responseBody) && is_object($responseBody)) {
                $context = get_object_vars($responseBody);
                
                $data['status'] = $context['status'] ?? '';
                $data['reason'] = $context['reason'] ?? '';

                if (!empty($data['code']) && !empty($data['status']) && !empty($data['reason'])) {
                    $errorDescribeData = cybersource_error_message($locale,"Payment",$data['code'],$data['status'],$data['reason']);

                    if (!empty($errorDescribeData)) {
                        $data['message'] = $errorDescribeData['message'];
                        $data['action'] = $errorDescribeData['action'];
                    }
                }
            }
        }

        Log::channel(config('cybersource.channel') ?: 'cybersource')
            ->emergency('订单id: '.($orderId ?? '0').' cybersource client '.$type.' response message:'. $data['message'],isset($context) && !empty($context) ? $context : $exception->getTrace());
        
        report($exception);

        return cybersource_return_error("error",$data);
    }
}