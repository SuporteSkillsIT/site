<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Finder
 */

/**
 * Copyright © 2015 Amasty. All rights reserved.
 */

namespace Amasty\Finder\Block\Adminhtml\Finder\Edit\Tab;

use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Widget\Tab\TabInterface;

class Main extends Generic implements TabInterface
{
    /**
     * @return \Magento\Framework\Phrase
     */
    public function getTabLabel()
    {
        return __('General');
    }

    /**
     * @return \Magento\Framework\Phrase
     */
    public function getTabTitle()
    {
        return __('General');
    }

    /**
     * @return bool
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * @return bool
     */
    public function isHidden()
    {
        return false;
    }

    /**
     * Prepare form before rendering HTML
     *
     * @return Generic
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    protected function _prepareForm()
    {
        $model = $this->_coreRegistry->registry('current_amasty_finder_finder');
        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();
        $form->setHtmlIdPrefix('finder_');
        $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('General')]);
        if ($model->getId()) {
            $fieldset->addField('id', 'hidden', ['name' => 'id']);
        }
        $fieldset->addField(
            'name',
            'text',
            ['name' => 'name', 'label' => __('Title'), 'title' => __('Title'), 'required' => true]
        );

        if (!$model->getId()) {
            $fieldset->addField(
                'cnt',
                'text',
                [
                    'name' => 'cnt',
                    'label' => __('Number of Dropdowns'),
                    'title' => __('Number of Dropdowns'),
                    'required' => true
                ]
            );
        }

        $fieldset->addField(
            'template',
            'select',
            [
                'name' => 'template',
                'label' => __('Template'),
                'title' => __('Template'),
                'required' => false,
                'values' => [
                    ['value' => 'horizontal', 'label' => __('Horizontal')],
                    ['value' => 'vertical', 'label' => __('Vertical')]
                ],
                'note' => __('The `horizontal` option is responsive and set by default')
            ]
        );

        $fieldset->addField(
            'custom_url',
            'text',
            [
                'name' => 'custom_url',
                'label' => __('Custom Destination URL'),
                'title' => __('Custom Destination URL'),
                'required' => false,
                'note' =>
                    __(
                        'Modify /amfinder/ url key.'
                    )
            ]
        );

        if ($model->getId()) {
            $fieldset->addField(
                'code_for_inserting',
                'label',
                [
                    'label' => __('Code for inserting'),
                    'title' => __('Code for inserting'),
                    'note' =>
                        'We do recommend to add the finder block to the root category if 
                        you are going to use finder on the home page or on the category without products'
                ]
            );
            $fieldset->addField(
                'code_for_inserting_in_layout',
                'label',
                [
                    'label' => __('Code for inserting in Layout Update XML'),
                    'title' => __('Code for inserting in Layout Update XML'),
                    'note' =>
                        'We do recommend to add the finder block to the root category if 
                        you are going to use finder on the home page or on the category without products'
                ]
            );
        }

        $form->setValues($model->getData());
        $form->addValues(
            [
                'id' => $model->getId(),
                'code_for_inserting' =>
                    '{{block class="Amasty\\Finder\\Block\\Form" block_id="finder_form"
                    location="cms" id="' . $model->getId() . '"}}'
            ]
        );

        $form->addValues(
            [
                'id' => $model->getId(),
                'code_for_inserting_in_layout' =>
                    '<referenceContainer name="content">
                         <block class="Amasty\Finder\Block\Form" name="amasty.finder.' . $model->getId() . '">
                             <arguments>
                                 <argument name="id" xsi:type="string">' . $model->getId() . '</argument>
                                 <argument name="location" xsi:type="string">xml</argument>
                             </arguments>
                         </block>
                     </referenceContainer>'
            ]
        );

        $this->setForm($form);
        return parent::_prepareForm();
    }
}
