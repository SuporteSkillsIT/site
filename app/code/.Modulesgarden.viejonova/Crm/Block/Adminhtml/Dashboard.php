<?php

namespace Modulesgarden\Crm\Block\Adminhtml;

require_once dirname(dirname(__DIR__)) . '/vendor/autoload.php';
require_once dirname(dirname(__DIR__)) . '/Services/SlimIntegration.php';

use \Magento\Backend\Block\Template;
use \Modulesgarden\Crm\Services\SlimIntegration;

class Dashboard extends Template
{

    protected $_resource;
    protected $slim;
    protected $_urlinterface;
    protected $session;
    protected $_adminSession;

    /**
     * @param Context $context
     * @param array $data
     */
    public function __construct(Template\Context $context,
            \Magento\Framework\UrlInterface $urlinterface,
            \Magento\Backend\Model\Session $session,
            \Magento\Backend\Model\Auth\Session $adminSession,
    //    \Magento\Framework\App\ResourceConnection $resource,  
            array $data = [])
    {
        parent::__construct($context, $data);
        $this->_urlinterface = $urlinterface;
        //  $this->_resource = $resource;
        $this->session = $session;
        $this->_adminSession = $adminSession;
        // $this->slim->run();
    }

    public function runApp()
    {
        $urls = array(
            'apiUrl' => $this->getApiUrl(),
            'viewUrl' => $this->getViewUrl(),
            'createCustomerUrl' => $this->getCreateCustomerUrl(),
            'createOrder' => $this->getCreateOrderUrl(),
            'viewOrder' => $this->getViewOrderUrl(),
            'viewCustomer' => $this->getViewCustomerUrl(),
            'invoiceUrl' => $this->getInvoiceUrl()
        );
        $this->slim = new SlimIntegration(false, $this->getLang(), $this->_adminSession->getUser()->getId(), $urls);
        // $this->slim->runSlimApp();
    }

    public function getViewUrl()
    {
        return $this->getViewFileUrl('Modulesgarden_Crm');
    }

    public function getCurrentUrl()
    {
        return $this->_urlinterface->getCurrentUrl();
    }

    public function getApiUrl()
    {
        return $this->getUrl('*/api/call');
    }
    
    public function getCreateCustomerUrl()
    {
        return $this->getUrl('customer/index/new');
    }

    public function getLang()
    {
        return $this->session->getData('locale');
    }

    public function getCreateOrderUrl()
    {
        return $this->getUrl('sales/order_create/index');
    }

    public function getViewOrderUrl()
    {
        return $this->getUrl('sales/order/view');
    }

    public function getViewCustomerUrl()
    {
        return $this->getUrl('customer/index/edit');
    }
    
    public function getInvoiceUrl()
    {
        return $this->getUrl('sales/order_invoice/view');
    }

}
