<?php
/**
 * Copyright Â© 2015 CommerceExtensions. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace CommerceExtensions\ProductattributesImportExport\Controller\Adminhtml\Data;

use Magento\Framework\Controller\ResultFactory;

class ImportPost extends \CommerceExtensions\ProductattributesImportExport\Controller\Adminhtml\Data
{
    /**
     * import action from import/export customer reviews
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
		// For M2 extension we propose to use component “Magento\Framework\File” instead of $_FILES .
        if ($this->getRequest()->isPost() && $this->_objectManager->create('Magento\MediaStorage\Model\File\Uploader', ['fileId' => 'import_rates_file'])) {
            try {
				$params = $this->getRequest()->getParams();
                $importHandler = $this->_objectManager->create('CommerceExtensions\ProductattributesImportExport\Model\Data\CsvImportHandler');
                $importHandler->importFromCsvFile($this->getRequest()->getFiles('import_rates_file'), $params);

                $this->messageManager->addSuccess(__('The Product Attributes have been imported.'));
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addError(__('Invalid file upload attempt: '. $e->getMessage()));
            }
        } else {
            $this->messageManager->addError(__('Invalid file upload attempt'));
        }
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $resultRedirect->setUrl($this->_redirect->getRedirectUrl());
        return $resultRedirect;
    }

    /**
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed(
            'CommerceExtensions_ProductattributesImportExport::import_export'
        );

    }
}