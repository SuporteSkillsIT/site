<?php
/**
 * Copyright © 2015 CommerceExtensions. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace CommerceExtensions\ProductattributesImportExport\Controller\Adminhtml\Data;

use Magento\Framework\Controller\ResultFactory;

class ImportExport extends \CommerceExtensions\ProductattributesImportExport\Controller\Adminhtml\Data
{
    /**
     * Import and export Page
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);

        $resultPage->setActiveMenu('CommerceExtensions_ProductattributesImportExport::system_convert_productattributes');
        $resultPage->addContent(
            $resultPage->getLayout()->createBlock('CommerceExtensions\ProductattributesImportExport\Block\Adminhtml\Data\ImportExportHeader')
        );
        $resultPage->addContent(
            $resultPage->getLayout()->createBlock('CommerceExtensions\ProductattributesImportExport\Block\Adminhtml\Data\ImportExport')
        );
        $resultPage->getConfig()->getTitle()->prepend(__('Product Attributes'));
        $resultPage->getConfig()->getTitle()->prepend(__('Import and Export Product Attributes'));
        return $resultPage;
    }

    /**
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('CommerceExtensions_ProductattributesImportExport::import_export');
    }
}
