<?php
namespace KJ\MageMail\Controller\Product;

use KJ\MageMail\Controller\Product;

class SaveReview extends Product
{
    public function execute()
    {
        try {
            $this->_saveReviewAction();
        } catch (\Exception $e) {
            $this->_jsonResponse(array(
                'success'               => false,
                'message'               => $e->getMessage(),
            ));
        }
    }
}