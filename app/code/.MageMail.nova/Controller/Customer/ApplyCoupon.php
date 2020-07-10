<?php
namespace KJ\MageMail\Controller\Customer;

use KJ\MageMail\Controller\Customer;

class ApplyCoupon extends Customer
{
    public function execute()
    {
        try {
            $this->_applyCouponAction();
        } catch (\Exception $e) {
            $this->_jsonResponse(array(
                'success'               => false,
                'message'               => $e->getMessage(),
                'should_save_in_cookie' => false,
                'should_destroy_cookie' => true,
            ));
        }
    }
}