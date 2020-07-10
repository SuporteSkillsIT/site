<?php
namespace KJ\MageMail\Controller\Customer;

use KJ\MageMail\Controller\Customer;

class SaveCart extends Customer
{
    public function execute()
    {
        try {
            $this->_saveCartAction();
        } catch (\Exception $e) {
            $this->_jsonResponse(array(
                'success'               => false,
                'message'               => $e->getMessage(),
            ));
        }
    }
}