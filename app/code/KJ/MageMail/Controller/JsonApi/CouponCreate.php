<?php
namespace KJ\MageMail\Controller\JsonApi;

use Braintree\Exception;
use KJ\MageMail\Controller\JsonApi;

class CouponCreate extends JsonApi
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