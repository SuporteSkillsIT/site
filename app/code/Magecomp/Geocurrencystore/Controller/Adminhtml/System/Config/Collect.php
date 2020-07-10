<?php
namespace Magecomp\Geocurrencystore\Controller\Adminhtml\System\Config;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Magecomp\Geocurrencystore\Helper\Data;

class Collect extends Action
{

    protected $resultJsonFactory;
    protected $_filesystem;
    protected $helper;

    public function __construct(
        Context $context,
        JsonFactory $resultJsonFactory,
        Data $helper,
        \Magento\Framework\Filesystem $_filesystem
    )
    {
        $this->resultJsonFactory = $resultJsonFactory;
        $this->helper = $helper;
        $this->_filesystem = $_filesystem;
        parent::__construct($context);
    }

    public function execute()
    {
        try
        {
            $zip_url    = "http://geolite.maxmind.com/download/geoip/database/GeoLiteCountry/GeoIP.dat.gz";
            $archive = $this->helper->getDatabaseDirectory()."/geoip/GeoIP.zip";

            file_put_contents($archive, fopen($zip_url, 'r'));

            $destination = $this->helper->getDatabaseDirectory()."/geoip/GeoIP.dat";

            $buffer_size = 4096; // read 4kb at a time
            $archive = gzopen($archive, 'rb');
            $dat = fopen($destination, 'wb');
            while(!gzeof($archive)) {
                fwrite($dat, gzread($archive, $buffer_size));
            }
            fclose($dat);
            gzclose($archive);

            $result = $this->resultJsonFactory->create();

            return $result->setData(['success' => true]);
        } catch (\Exception $e)
        {
            $result = $this->resultJsonFactory->create();

            return $result->setData(['success' => false]);
        }
    }

    protected function _isAllowed()
    {
        return true;
    }

}