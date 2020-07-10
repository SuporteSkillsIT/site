<?php
namespace KJ\MageMail\Controller\Adminhtml\Manage;

use Magento\Backend\App\Action;
use Magento\Framework\App\Config\Storage\WriterInterface;

class SaveConnectedStatus extends Action
{
    protected $_config;
    protected $_helper;
    protected $_cacheManager;
    protected $_jsonHelper;

    public function __construct(Action\Context $context,
            \KJ\MageMail\Helper\Data $mageMailHelper,
            \Magento\Framework\App\Cache\Manager $cacheManager,
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
        $this->_config->save('kj_magemail/settings/is_connected', true);
        $this->_config->save('kj_magemail/settings/web_username', $this->getRequest()->getParam('web_username'));
        $this->_cacheManager->clean(['config']);

        $this->_jsonResponse(array(
            'success' => true,
            'message' => "Saved connection status to config",
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