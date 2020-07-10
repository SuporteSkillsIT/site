<?php
namespace KJ\MageMail\Controller\Product;

use KJ\MageMail\Controller\Product;

class Image extends Product
{
    public function execute()
    {
        try {
            $this->_imageAction();
        } catch (\Exception $e) {
            $this->getResponse()->setRedirect($this->_placeholderImagePath());
        }
    }
}