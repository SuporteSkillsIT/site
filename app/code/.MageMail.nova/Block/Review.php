<?php
namespace KJ\MageMail\Block;

use Magento\Framework\View\Element\Template;

class Review extends Template
{    protected $request;
    protected $helper;

    public function __construct(
        \Magento\Framework\App\Request\Http $request,
        \KJ\MageMail\Helper\Data $mageMailHelper,
        Template\Context $context, array $data)
    {
        parent::__construct($context, $data);
        $this->helper = $mageMailHelper;
        $this->request = $request;
    }

    public function getPost()
    {
        return $this->request->getPost();
    }

    public function getHelper()
    {
        return $this->helper;
    }
}