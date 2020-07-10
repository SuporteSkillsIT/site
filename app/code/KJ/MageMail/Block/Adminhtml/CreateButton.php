<?php
namespace KJ\MageMail\Block\Adminhtml;


/**
 * @method KJ_MageMail_Block_Adminhtml_ConnectButton setElement($element)
 */
class CreateButton extends \Magento\Config\Block\System\Config\Form\Field
{
    protected function _getElementHtml(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        $this->setElement($element);

        /** @var Mage_Adminhtml_Block_Widget_Button $block */
        $block =  $this->getLayout()->createBlock('Magento\Backend\Block\Widget\Button');
        $block->setData('type', 'button')
            ->setData('class', 'scalable connect')
            ->setData('label', 'Register New MageMail Account')
            ->setData('on_click', "quickCreateMageMail();");

        $html = $block->toHtml();

        return $html;
    }
}