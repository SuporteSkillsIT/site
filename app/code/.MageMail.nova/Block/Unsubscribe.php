<?php
namespace KJ\MageMail\Block;


use Magento\Framework\View\Element\Template;

class Unsubscribe extends Template
{

    protected $request;

    public function __construct(
        \Magento\Framework\App\Request\Http $request,
        Template\Context $context, array $data)
    {
        parent::__construct($context, $data);
        $this->request = $request;
    }

    public function getMmRecipient()
    {
        return $this->request->getParam('mm_recipient');
    }
}