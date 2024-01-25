<?php

namespace Smbear\Cybersource\Facades;

use Illuminate\Support\Facades\Facade;

class CyberSourceFacades extends Facade {
    protected static function getFacadeAccessor():string
    {
        return 'cybersource';
    }
}