<?php
namespace KJ\MageMail\Controller\Customer;

use KJ\MageMail\Controller\Customer;
class CancelCoupon extends Customer
{
    public function execute()
    {
        try {
            $this->_cancelCouponAction();
        } catch (\Exception $e) {
            $this->_jsonResponse(array(
                'success'               => false,
                'message'               => $e->getMessage(),
                'should_destroy_cookie' => false,
            ));
        }
    }
}