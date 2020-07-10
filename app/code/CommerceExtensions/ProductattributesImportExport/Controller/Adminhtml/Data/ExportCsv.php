<?php
/**
 * Copyright © 2015 CommerceExtensions. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace CommerceExtensions\ProductattributesImportExport\Controller\Adminhtml\Data;

use Magento\Framework\App\ResponseInterface;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Controller\ResultFactory;

class ExportCsv extends \CommerceExtensions\ProductattributesImportExport\Controller\Adminhtml\Data
{
    /**
     * Export Data grid to CSV format
     *
     * @return ResponseInterface
     */
    public function execute()
    {
        /** @var \Magento\Framework\View\Result\Layout $resultLayout */
        $resultLayout = $this->resultFactory->create(ResultFactory::TYPE_LAYOUT);
        $content = $resultLayout->getLayout()->getChildBlock('adminhtml.productattributes.data.grid', 'grid.export');

        return $this->fileFactory->create(
            'export_product_attributes.csv',
            $content->getCsvFile(),
            DirectoryList::VAR_DIR
        );
    }
}
