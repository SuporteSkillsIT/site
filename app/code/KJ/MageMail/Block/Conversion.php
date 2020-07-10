<?php
namespace KJ\MageMail\Block;
use Magento\Framework\View\Element\Template;

class Conversion extends Template
{
    protected $session;

    public function __construct(
        \Magento\Checkout\Model\Session $checkoutSession,
        Template\Context $context,
        array $data)
    {
        parent::__construct($context, $data);
        $this->session = $checkoutSession;
    }

    public function getLastOrder()
    {
        return $this->session->getLastRealOrder();
    }

}