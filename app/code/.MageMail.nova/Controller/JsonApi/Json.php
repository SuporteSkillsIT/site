<?php
namespace KJ\MageMail\Controller\JsonApi;
use KJ\MageMail\Controller\JsonApi;

class Json extends JsonApi
{
    public function execute()
    {
        $this->_jsonResponse(array(
            'success'   => true,
        ));
    }
}