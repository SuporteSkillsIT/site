<?php
namespace KJ\MageMail\Controller\Customer;

use KJ\MageMail\Controller\Customer;

class GetCart extends Customer
{
    public function execute()
    {
        try {
            $this->_getCartAction();
        } catch (\Exception $e) {
            $this->_jsonResponse(array(
                'success'               => false,
                'message'               => $e->getMessage(),
            ));
        }
    }
}