<?php

namespace Modulesgarden\Crm\Controller\Adminhtml\Api;

class Call extends \Magento\Backend\App\AbstractAction
{

    public function execute()
    {
        $this->_view->loadLayout();
        $this->_view->renderLayout();
    }

    public function _processUrlKeys()
    {
        return true;
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Modulesgarden_Crm::index');
    }

}
