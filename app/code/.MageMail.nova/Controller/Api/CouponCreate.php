<?php
namespace KJ\MageMail\Controller\Api;

use KJ\MageMail\Controller\Api;

class CouponCreate extends Api
{
    public function execute()
    {
        try {
            $this->_couponCreate();
        } catch (\Exception $e) {
            $this->_jsonResponse(array(
                'success'               => false,
                'message'               => $e->getMessage(),
            ));
        }
    }
}