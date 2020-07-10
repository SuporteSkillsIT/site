<?php

namespace BoostMyShop\Supplier\Controller\Adminhtml\Order;

use Magento\Framework\App\Filesystem\DirectoryList;

class ExportPo extends \BoostMyShop\Supplier\Controller\Adminhtml\Order
{
    public function execute()
    {
        $poId = (int)$this->getRequest()->getParam('po_id');
        $order = $this->_orderFactory->create()->load($poId);

        $headers = array('part number','quantity','coment');

        try{

            $csv = $this->_csvImport->getCsvFile($headers,$order);
            $date = $this->_timezoneInterface->date()->format('Y-m-d_H');

            return $this->_fileFactory->create(
                'po_product' . $date .' .csv',
                $csv,
                DirectoryList::VAR_DIR,
                'application/csv'
            );

        } catch(\Exception $e){
            $this->messageManager->addError(__('An error occurred : '.$e->getMessage()));
            $this->_redirect('*/*/index');
        }

        $this->_redirect('*/*/Edit', ['po_id' => $poId]);
    }

}
