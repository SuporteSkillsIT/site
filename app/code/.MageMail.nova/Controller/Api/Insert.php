<?php

namespace KJ\MageMail\Controller\Api;

use KJ\MageMail\Controller\Api;

class Insert extends Api
{
    public function execute()
    {
        try {
            $this->_insertAction();
        } catch (\Exception $e) {
            $this->_jsonResponse(array(
                'success'   => false,
                'message'   => "Problem with query: " . $e->getMessage(),
            ));
        }
    }
}