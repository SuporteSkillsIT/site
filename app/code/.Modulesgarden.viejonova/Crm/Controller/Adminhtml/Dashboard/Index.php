<?php

namespace Modulesgarden\Crm\Controller\Adminhtml\Dashboard;

class Index extends \Magento\Backend\App\Action
{

    /**
      Index Action
     * @return void
     */
    public function execute()
    {
        include_once dirname(dirname(dirname(__DIR__))) . DIRECTORY_SEPARATOR . 'Model' . DIRECTORY_SEPARATOR . 'lic.php';
        $license_check = crm_for_magento_2_dot_0_license_886();
        if ($license_check['status'] != 'Active') {
            $error_message = isset($license_custom_error_message) ? $license_custom_error_message : ("License {$license_check['status']}" . ($license_check['description'] ? ": {$license_check['description']}" : ""));
            $this->messageManager->addError(__($error_message));
            header("Location: " . $this->getUrl('admin/dashboard/index/'));
            exit();
        }
        $this->_view->loadLayout();
        $this->_view->renderLayout();
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Modulesgarden_Crm::index');
    }

}
