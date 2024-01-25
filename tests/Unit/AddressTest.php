<?php

namespace Smbear\Cybersource\Tests\Unit;

use CyberSource\ApiClient;
use CyberSource\Authentication\Core\MerchantConfiguration;
use Smbear\Cybersource\Tests\TestCase;

class AddressTest extends TestCase {
    public function testClient()
    {
        $config = new MerchantConfiguration();

        $config->setMerchantID(trim($this->merchantID));
    }

    public function testAddress() {

    }
}