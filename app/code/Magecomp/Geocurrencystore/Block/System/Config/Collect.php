<?php
namespace Magecomp\Geocurrencystore\Block\System\Config;

use Magento\Backend\Block\Template\Context;
use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Data\Form\Element\AbstractElement;

class Collect extends Field
{
    protected $_template = 'Magecomp_Geocurrencystore::system/config/collect.phtml';

    public function __construct(
    Context $context,
    array $data = []
    ) {
    parent::__construct($context, $data);
    }

    public function render(AbstractElement $element)
    {
        $element->unsScope()->unsCanUseWebsiteValue()->unsCanUseDefaultValue();
        return parent::render($element);
    }

    protected function _getElementHtml(AbstractElement $element)
    {
        return $this->_toHtml();
    }

    public function getAjaxUrl()
    {
        return $this->getUrl('geocurrencystore/system_config/collect');
    }

    public function getButtonHtml()
    {
        $button = $this->getLayout()->createBlock(
        'Magento\Backend\Block\Widget\Button'
        )->setData(
            [
            'id' => 'collect_button',
            'label' => __('Update Database'),
            ]
        );

        return $button->toHtml();
    }
}