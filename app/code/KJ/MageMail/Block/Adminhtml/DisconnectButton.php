<?php
namespace KJ\MageMail\Block\Adminhtml;

/**
 * @method KJ_MageMail_Block_Adminhtml_DisconnectButton setElement($element)
 */
class DisconnectButton extends \Magento\Config\Block\System\Config\Form\Field
{
    protected function _getElementHtml(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        $this->setElement($element);

        /** @var Mage_Adminhtml_Block_Widget_Button $block */
        $block =  $this->getLayout()->createBlock('Magento\Backend\Block\Widget\Button');
        $block->setData('type', 'button')
            ->setData('class', 'delete scalable magemail disconnect')
            ->setData('label', 'Disconnect from MageMail');

        $html = $block->toHtml();

        return $html;
    }
}