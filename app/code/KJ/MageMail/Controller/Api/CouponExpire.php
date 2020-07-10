<?php
namespace KJ\MageMail\Controller\Api;

use KJ\MageMail\Controller\Api;

class CouponExpire extends Api
{
    public function execute()
    {
        try {
            $this->_couponExpire();
        } catch (\Exception $e) {
            $this->_jsonResponse(array(
                'success'               => false,
                'message'               => $e->getMessage(),
            ));
        }
    }
}