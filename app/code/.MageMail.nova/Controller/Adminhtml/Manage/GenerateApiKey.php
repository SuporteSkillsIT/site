<?php
namespace KJ\MageMail\Controller\Adminhtml\Manage;

use Magento\Backend\App\Action;
use Magento\Framework\App\Cache\Manager as CacheManager;
use Magento\Framework\App\Cache\Type\Config as CacheTypeConfig;
use Magento\Framework\App\Config\Storage\WriterInterface;

class GenerateApiKey extends Action
{
    protected $_config;
    protected $_helper;
    protected $_cacheManager;
    protected $_jsonHelper;

    public function __construct(Action\Context $context,
        \KJ\MageMail\Helper\Data $mageMailHelper,
        CacheManager $cacheManager,
        \Magento\Framework\Json\Helper\Data $jsonHelper,
        WriterInterface $writer)
    {
        parent::__construct($context);
        $this->_cacheManager = $cacheManager;
        $this->_config = $writer;
        $this->_jsonHelper = $jsonHelper;
        $this->_helper = $mageMailHelper;
    }


    public function execute()
    {
        $apiKey = md5('api_key' . microtime() . $this->_helper->getCryptKey());
        $apiRoute = md5('api_route' . microtime() . $this->_helper->getCryptKey());
        $this->_config->save('kj_magemail/settings/enable_api', true);
        $this->_config->save('kj_magemail/settings/enable_api_write_access', true);
        $this->_config->save('kj_magemail/settings/enable_javascript', true);
        $this->_config->save('kj_magemail/advanced/api_key', $apiKey);
        $this->_config->save('kj_magemail/advanced/api_route', $apiRoute);
        $this->_cacheManager->clean(['config']);

        $this->_jsonResponse(array(
            'success'   => true,
            'message'   => "Generated api key and route",
            'api_key'   => $apiKey,
            'api_route' => $apiRoute,
        ));
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('admin/system/config/newsletter');
    }

    protected function _jsonResponse($data)
    {
        $this->getResponse()->setHeader('Content-Type', 'application/json');
        $this->getResponse()->setBody($this->_jsonHelper->jsonEncode($data));
    }
}