<?php
namespace KJ\MageMail\Controller\Customer;

use KJ\MageMail\Controller\Customer;

class CreateTestQuote extends Customer
{
    public function execute()
    {
        try {
            $this->_createTestQuoteAction();
        } catch (\Exception $e) {
            $this->_jsonResponse(array(
                'success'               => false,
                'message'               => $e->getMessage(),
            ));
        }
    }
}