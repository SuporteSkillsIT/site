<?php
namespace KJ\MageMail\Controller\JsonApi;
use KJ\MageMail\Controller\JsonApi;

class Query extends JsonApi
{
    public function execute()
    {
        try {
            $this->_queryAction();
        } catch (\Exception $e) {
            $this->_jsonResponse(array(
                'success'   => false,
                'message'   => $e->getMessage(),
            ));
        }
    }
}