<?php
namespace KJ\MageMail\Controller\Api;

use KJ\MageMail\Controller\Api;

class CreateAheadworksSubscriptionFields extends Api
{
    public function execute()
    {
        try {
            $this->_createAheadworksSubscriptionFields();
        } catch (\Exception $e) {
            $this->_jsonResponse(array(
                'success'   => false,
                'message'   => "Problem with request: " . $e->getMessage(),
            ));
        }
    }
}