<?php

if (!function_exists('cybersource_return_success')) {
    function cybersource_return_success(string $message, array $data = []): array
    {
        return [
            'status' => 'success',
            'code' => 200,
            'message' => $message,
            'data' => $data
        ];
    }
}

if (!function_exists('cybersource_return_error')) {
    function cybersource_return_error(string $message, array $data = []): array
    {
        return [
            'status' => 'error',
            'code' => 500,
            'message' => $message,
            'data' => $data
        ];
    }
}

if (!function_exists('cybersource_error_message')) {
    function cybersource_error_message(string $local,$type,$code,$status,$reason): array
    {
        $enData =  [
            'PAYMENT' => [
                201 => [
                    [
                        "status"=> "AUTHORIZED",
                        "reason"=> "N/A",
                        "message"=> "Successful transaction.\n",
                        "action"=> "N/A"
                    ],
                    [
                        "status"=> "PARTIAL_AUTHORIZED",
                        "reason"=> "N/A",
                        "message"=> "Partial amount was approved.\n",
                        "action"=> "N/A"
                    ],
                    [
                        "status"=> "AUTHORIZED_PENDING_REVIEW",
                        "reason"=> "AVS_FAILED",
                        "message"=> "The authorization request was approved by the issuing bank but declined by CyberSource because it\ndid not pass the Address Verification Service (AVS) check.\n",
                        "action"=> "You can capture the authorization, but consider reviewing the order for the possibility of fraud.\n"
                    ],
                    [
                        "status"=> "AUTHORIZED_PENDING_REVIEW",
                        "reason"=> "CONTACT_PROCESSOR",
                        "message"=> "The issuing bank has questions about the request. You do not receive an authorization code\nprogrammatically, but you might receive one verbally by calling the processor.\n",
                        "action"=> "Call your processor to possibly receive a verbal authorization. For contact phone numbers, refer to your\nmerchant bank information.\n"
                    ],
                    [
                        "status"=> "DECLINED",
                        "reason"=> "EXPIRED_CARD",
                        "message"=> "Expired card. You might also receive this if the expiration date you provided does not match the date the\nissuing bank has on file.\n\nNote=> The ccCreditService does not check the expiration date; instead, it passes the request to the payment\nprocessor. If the payment processor allows issuance of credits to expired cards, CyberSource does not limit this\nfunctionality.\n",
                        "action"=> "Request a different card or other form of payment.\n"
                    ],
                    [
                        "status"=> "DECLINED",
                        "reason"=> "PROCESSOR_DECLINED",
                        "message"=> "General decline of the card. No other information provided by the issuing bank.\n",
                        "action"=> "Request a different card or other form of payment.\n"
                    ],
                    [
                        "status"=> "DECLINED",
                        "reason"=> "PROCESSOR_ERROR",
                        "message"=> "Unauthorized Transaction=> Pick up card\n",
                        "action"=> "Refer the transaction to your customer support center for manual review.\n"
                    ],
                    [
                        "status"=> "DECLINED",
                        "reason"=> "INSUFFICIENT_FUND",
                        "message"=> "Insufficient funds in the account.\n",
                        "action"=> "Request a different card or other form of payment.\n"
                    ],
                    [
                        "status"=> "DECLINED",
                        "reason"=> "STOLEN_LOST_CARD",
                        "message"=> "Stolen or lost card.\n",
                        "action"=> "Refer the transaction to your customer support center for manual review.\n"
                    ],
                    [
                        "status"=> "DECLINED",
                        "reason"=> "ISSUER_UNAVAILABLE",
                        "message"=> "Issuing bank unavailable.\n",
                        "action"=> "Wait a few minutes and resend the request.\n"
                    ],
                    [
                        "status"=> "DECLINED",
                        "reason"=> "UNAUTHORIZED_CARD",
                        "message"=> "Inactive card or card not authorized for card-not-present transactions.\n",
                        "action"=> "Request a different card or other form of payment.\n"
                    ],
                    [
                        "status"=> "DECLINED",
                        "reason"=> "CVN_NOT_MATCH",
                        "message"=> "Card verification number (CVN) did not match.\n",
                        "action"=> "Request a different card or other form of payment.\n"
                    ],
                    [
                        "status"=> "DECLINED",
                        "reason"=> "EXCEEDS_CREDIT_LIMIT",
                        "message"=> "The card has reached the credit limit.\n",
                        "action"=> "Request a different card or other form of payment.\n"
                    ],
                    [
                        "status"=> "DECLINED",
                        "reason"=> "INVALID_CVN",
                        "message"=> "Invalid Card Verification Number (CVN).\n",
                        "action"=> "Request a different card or other form of payment.\n"
                    ],
                    [
                        "status"=> "DECLINED",
                        "reason"=> "DECLINED_CHECK",
                        "message"=> "Generic Decline.\n",
                        "action"=> "Request a different form of payment.\n"
                    ],
                    [
                        "status"=> "DECLINED",
                        "reason"=> "BLACKLISTED_CUSTOMER",
                        "message"=> "For eCheck payment, the customer matched an entry on the processor's negative file.\n",
                        "action"=> "Review the order and contact the payment processor.\n"
                    ],
                    [
                        "status"=> "DECLINED",
                        "reason"=> "SUSPENDED_ACCOUNT",
                        "message"=> "For eCheck payment, Customer's account is frozen.\n",
                        "action"=> "Review the order or request a different form of payment.\n"
                    ],
                    [
                        "status"=> "DECLINED",
                        "reason"=> "PAYMENT_REFUSED",
                        "message"=> "Decline - (A) Merchant account or payer’s account is not set up to process such transactions. (B) Insufficient\nfunds in the payer’s funding source associated with the account, or transaction declined by bank. (C) A\nparticular action is not permitted, for example=> capture refused, or the authorization has already been\ncaptured. (D) Fraud setting for the seller is blocking such payments.\n",
                        "action"=> "Try a different payment method or a different account.\n"
                    ],
                    [
                        "status"=> "AUTHORIZED_PENDING_REVIEW",
                        "reason"=> "CV_FAILED",
                        "message"=> "The authorization request was approved by the issuing bank but declined by CyberSource because it\ndid not pass the card verification number (CVN) check.\n",
                        "action"=> "You can capture the authorization, but consider reviewing the order for the possibility of fraud.\n"
                    ],
                    [
                        "status"=> "DECLINED",
                        "reason"=> "INVALID_ACCOUNT",
                        "message"=> "Invalid account number.\n",
                        "action"=> "Request a different card or other form of payment.\n"
                    ],
                    [
                        "status"=> "DECLINED",
                        "reason"=> "GENERAL_DECLINE",
                        "message"=> "General decline by the processor.\n",
                        "action"=> "Request a different card or other form of payment.\n"
                    ],
                    [
                        "status"=> "INVALID_REQUEST",
                        "reason"=> "INVALID_MERCHANT_CONFIGURATION",
                        "message"=> "There is a problem with your CyberSource merchant configuration.\n",
                        "action"=> "Do not resend the request. Contact Customer Support to correct the configuration problem.\n"
                    ],
                    [
                        "status"=> "DECLINED",
                        "reason"=> "BOLETO_DECLINED",
                        "message"=> "The boleto request was declined by your processor.\n",
                        "action"=> "Request a different card or other form of payment.\n"
                    ],
                    [
                        "status"=> "SERVER_ERROR",
                        "reason"=> "PROCESSOR_TIMEOUT",
                        "message"=> "The request was received, but there was a timeout at the payment processor.\n",
                        "action"=> "To avoid duplicating the transaction, do not resend the request until you have reviewed the transaction status\nin the Enterprise Business Center.\n"
                    ],
                    [
                        "status"=> "DECLINED",
                        "reason"=> "DEBIT_CARD_USAGE_LIMIT_EXCEEDED",
                        "message"=> "The Pinless Debit card's use frequency or maximum amount per use has been exceeded.\n",
                        "action"=> "Request a different card or other form of payment.\n"
                    ],
                    [
                        "status"=> "AUTHORIZED_RISK_DECLINED",
                        "reason"=> "SCORE_EXCEEDS_THRESHOLD",
                        "message"=> "Soft Decline - Fraud score exceeds threshold.\n",
                        "action"=> "Request or retry an authorization later.\n"
                    ],
                    [
                        "status"=> "PENDING_AUTHENTICATION",
                        "reason"=> "CONSUMER_AUTHENTICATION_REQUIRED",
                        "message"=> "The cardholder is enrolled in [[payerAuthentication]]. Please authenticate the cardholder before continuing with the transaction.\n",
                        "action"=> "Request or retry an authorization later.\n"
                    ],
                    [
                        "status"=> "DECLINED",
                        "reason"=> "CONSUMER_AUTHENTICATION_FAILED",
                        "message"=> "Encountered a [[payerAuthentication]] problem. Payer could not be authenticated.\n",
                        "action"=> "Request or retry an authorization later.\n"
                    ],
                    [
                        "status"=> "AUTHORIZED_PENDING_REVIEW",
                        "reason"=> "DECISION_PROFILE_REVIEW",
                        "message"=> "The order is marked for review by [[decisionManager]]\n",
                        "action"=> "Request or retry an authorization later.\n"
                    ],
                    [
                        "status"=> "AUTHORIZED_RISK_DECLINED",
                        "reason"=> "DECISION_PROFILE_REJECT",
                        "message"=> "The order has been rejected by [[decisionManager]]\n",
                        "action"=> "Request or retry an authorization later.\n"
                    ]
                ],
                400 => [
                    [
                        "status"=> "INVALID_REQUEST",
                        "reason"=> "MISSING_FIELD",
                        "message"=> "The request is missing one or more fields.\n",
                        "action"=> "See the reply fields statusInformation.details[] for which fields are missing. Resend the request with the\ncorrect information.\n"
                    ],
                    [
                        "status"=> "INVALID_REQUEST",
                        "reason"=> "INVALID_DATA",
                        "message"=> "One or more fields in the request contains invalid data.\n",
                        "action"=> "See the reply fields statusInformation.details[] for which fields are invalid. Resend the request with the\ncorrect information.\n"
                    ],
                    [
                        "status"=> "INVALID_REQUEST",
                        "reason"=> "DUPLICATE_REQUEST",
                        "message"=> "The merchantReferenceCode sent with this authorization request matches the merchantReferenceCode of another\nauthorization request that you sent in the last 15 minutes.\n",
                        "action"=> "Resend the request with a unique merchantReferenceCode value.\n"
                    ],
                    [
                        "status"=> "INVALID_REQUEST",
                        "reason"=> "CARD_TYPE_NOT_ACCEPTED",
                        "message"=> "The card type is not accepted by the payment processor.\n",
                        "action"=> "Contact your merchant bank to confirm that your account is set up to receive the card in question.\n"
                    ],
                    [
                        "status"=> "INVALID_REQUEST",
                        "reason"=> "PROCESSOR_UNAVAILABLE",
                        "message"=> "Processor failure.\n",
                        "action"=> "Wait a few minutes and resend the request.\n"
                    ],
                    [
                        "status"=> "INVALID_REQUEST",
                        "reason"=> "INVALID_AMOUNT",
                        "message"=> "The requested transaction amount must match the previous transaction amount.\n",
                        "action"=> "Correct the amount and resend the request.\n"
                    ],
                    [
                        "status"=> "INVALID_REQUEST",
                        "reason"=> "INVALID_CARD_TYPE",
                        "message"=> "The card type sent is invalid or does not correlate with the credit card number.\n",
                        "action"=> "Confirm that the card type correlates with the credit card number specified in the request, then resend the\nrequest.\n"
                    ]
                ],
                502 => [
                    [
                        "status"=> "SERVER_ERROR",
                        "reason"=> "SYSTEM_ERROR",
                        "message"=> "General system failure.\n",
                        "action"=> "See the documentation for your CyberSource client (SDK) for information about how to handle retries in the case\nof system errors.\n"
                    ],
                    [
                        "status"=> "SERVER_ERROR",
                        "reason"=> "SERVER_TIMEOUT",
                        "message"=> "The request was received but there was a server timeout. This error does not include timeouts between the client\nand the server.\n",
                        "action"=> "To avoid duplicating the transaction, do not resend the request until you have reviewed the transaction status\nin the Business Center. See the documentation for your CyberSource client (SDK) for information about how to\nhandle retries in the case of system errors.\n"
                    ],
                    [
                        "status"=> "SERVER_ERROR",
                        "reason"=> "SERVICE_TIMEOUT",
                        "message"=> "The request was received, but a service did not finish running in time.\n",
                        "action"=> "To avoid duplicating the transaction, do not resend the request until you have reviewed the transaction status\nin the Enterprise Business Center. See the documentation for your CyberSource client (SDK) for information about\nhow to handle retries in the case of system errors.\n"
                    ],
                    [
                        "status"=> "SERVER_ERROR",
                        "reason"=> "INVALID_OR_MISSING_CONFIG",
                        "message"=> "Error - Invalid or missing merchant configuration\n",
                        "action"=> "Do not resend the request. Contact Customer Support to correct the configuration problem.\n"
                    ]
                ]
            ]
        ];

        $data = $enData[strtoupper($type)][$code] ?? [];

        if (empty($data)) {
            return [];
        }

        foreach ($data as $key => $value) {
            if (strtoupper($value['status']) == $status &&  strtoupper($value['reason']) == $reason) {
                return [
                    'action' => $value['action'] ?? '',
                    'message' => $value['message'] ?? ''
                ];
            }
        }
        
        return [];
    }
}