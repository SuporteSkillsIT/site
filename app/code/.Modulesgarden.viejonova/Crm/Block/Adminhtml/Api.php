<?php

namespace Modulesgarden\Crm\Block\Adminhtml;

error_reporting(E_ALL);
ini_set('display_errors', '1');
require_once dirname(dirname(__DIR__)) . '/vendor/autoload.php';
require_once dirname(dirname(__DIR__)) . '/Services/SlimIntegration.php';

use \Magento\Backend\Block\Template;

class Api extends Template
{

    protected $_resource;
    protected $slimApp;
    protected $_adminSession;

    /**
     * @param Context $context
     * @param \Magento\Backend\Model\Auth\Session $adminSession 
     * @param array $data
     */
    public function __construct(Template\Context $context,
            \Magento\Backend\Model\Auth\Session $adminSession, array $data = []
    )
    {
        parent::__construct($context, $data);
        $this->_adminSession = $adminSession;
        $urls = array(
            'apiUrl' => $this->getApiUrl(),
            'viewUrl' => $this->getViewUrl(),
            'createCustomerUrl' => null,
            'createOrder' => null,
            'viewOrder' => null,
            'viewCustomer' => null
        );
        $this->slimApp = new \Modulesgarden\Crm\Services\SlimIntegration(true, 'en_US', $this->_adminSession->getUser()->getId(), $urls);
    }

    public function getViewUrl()
    {
        return $this->getViewFileUrl('Modulesgarden_Crm');
    }

    public function getApiUrl()
    {
        return $this->getUrl('*/api/call');
    }

}
