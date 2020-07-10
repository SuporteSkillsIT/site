<?php
namespace KJ\MageMail\Block;
use Magento\Framework\View\Element\Template;

class Footer extends Template
{
    protected $_helper;

    public function __construct(\KJ\MageMail\Helper\Data $mageMailHelper,
        Template\Context $context, array $data)
    {
        parent::__construct($context, $data);
        $this->_helper = $mageMailHelper;
    }

    public function getHelper()
    {
        return $this->_helper;
    }
}