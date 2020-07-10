<?php
namespace KJ\MageMail\Controller\Adminhtml\Manage;

use Magento\Backend\App\Action;
use Magento\Framework\App\Config\Storage\WriterInterface;
use Magento\Framework\App\Cache\Manager as CacheManager;


class Disconnect extends Action
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
        $this->_config->save('kj_magemail/settings/enable_api', false);
        $this->_config->save('kj_magemail/settings/enable_api_write_access', false);
        $this->_config->save('kj_magemail/settings/enable_javascript', false);
        $this->_config->save('kj_magemail/advanced/api_key', null);
        $this->_config->save('kj_magemail/advanced/api_route', null);
        $this->_config->save('kj_magemail/settings/is_connected', false);
        $this->_config->save('kj_magemail/settings/web_username', null);
        $this->_config->save('kj_magemail/settings/web_password', null);
        $this->_cacheManager->clean(['config']);

        $this->getMessageManager()->addSuccessMessage("Disconnected from MageMail");
        $this->getResponse()->setRedirect($this->_backendUrl->getUrl('adminhtml/system_config/edit/section/newsletter'));
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('admin/system/config/newsletter');
    }
}