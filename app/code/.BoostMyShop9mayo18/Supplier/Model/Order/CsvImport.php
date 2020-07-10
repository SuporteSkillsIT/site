<?php

namespace BoostMyShop\Supplier\Model\Order;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem;
use Magento\Framework\Filesystem\Directory\WriteInterface;

class CsvImport
{

    protected $_orderProductFactory;
    protected $_httpFactory;
    protected $_dir;
    protected $_uploaderFactory;
    protected $_importHandler;

    public function __construct(
        Filesystem $filesystem,
        \BoostMyShop\Supplier\Model\ResourceModel\Order\Product\CollectionFactory $orderProductFactory,
        \Magento\Framework\HTTP\Adapter\FileTransferFactory $httpFactory,
        \Magento\Framework\Filesystem\DirectoryList $dir,
        \BoostMyShop\Supplier\Model\Order\ProductsImportHandler $importHandler,
        \Magento\Framework\File\UploaderFactory $uploaderFactory
    ) {
        $this->directory = $filesystem->getDirectoryWrite(DirectoryList::VAR_DIR);
        $this->_orderProductFactory = $orderProductFactory;
        $this->_httpFactory = $httpFactory;
        $this->_dir = $dir;
        $this->_uploaderFactory = $uploaderFactory;
        $this->_importHandler = $importHandler;
    }

    public function getCsvFile($headers,$order)
    {
        $orderdetail = array();
        $name = strtotime("now");
        $file = 'export/orderexport' . $name . '.csv';
        $this->directory->create('export');
        $stream = $this->directory->openFile($file, 'w+');
        $stream->lock();



        /*Header for PO products */
        $stream->writeCsv($headers);
        $collection = $this->_orderProductFactory->create();
        $collection->addOrderFilter($order->getId());

        foreach($collection as $product){
            $orderdetail['sku'] = $product->getData('pop_sku');
            $orderdetail['qty'] = $product->getData('pop_qty');


            $stream->writeCsv($orderdetail);
        }

        $stream->unlock();
        $stream->close();
        return [
            'type' => 'filename',
            'value' => $file,
            'rm' => true  // can delete file after use
        ];
    }

    /**
     * @param \BoostMyShop\Supplier\Model\Order $order
     * @param int $poId
     * @return void
     */
    public function checkPoImport($order, $poId)
    {
        try
        {
            $adapter = $this->_httpFactory->create();
            if ($adapter->isValid('import_file')) {
                $destinationFolder = $this->_dir->getPath(\Magento\Framework\App\Filesystem\DirectoryList::VAR_DIR);
                $uploader = $this->_uploaderFactory->create(array('fileId' => 'import_file'));
                $uploader->setAllowRenameFiles(true);
                $uploader->setAllowedExtensions(['csv']);
                $uploader->setFilesDispersion(true);
                $uploader->setAllowCreateFolders(true);
                $result = $uploader->save($destinationFolder);
                $fullPath = $result['path'].$result['file'];

                return $this->_importHandler->importFromCsvFile($poId, $order, $fullPath);
            }

        }
        catch(\Exception $ex)
        {
            throw new \Exception($ex->getMessage());
        }

    }

}
