<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Finder
 */

/**
 * Copyright © 2015 Amasty. All rights reserved.
 */

namespace Amasty\Finder\Block\Adminhtml\Value\Edit;

class Form extends \Magento\Backend\Block\Widget\Form\Generic
{
    const LABEL = 'label';
    const NAME = 'name';
    const TITLE = 'title';

    /**
     * @var \Amasty\Finder\Api\ValueRepositoryInterface
     */
    private $valueRepository;

    /**
     * @var \Amasty\Finder\Api\DropdownRepositoryInterface
     */
    private $dropdownRepository;

    /**
     * Form constructor.
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param \Amasty\Finder\Api\ValueRepositoryInterface $valueRepository
     * @param \Amasty\Finder\Api\DropdownRepositoryInterface $dropdownRepository
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Amasty\Finder\Api\ValueRepositoryInterface $valueRepository,
        \Amasty\Finder\Api\DropdownRepositoryInterface $dropdownRepository,
        array $data
    ) {
        $this->valueRepository = $valueRepository;
        $this->dropdownRepository = $dropdownRepository;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * Constructor
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('amasty_finder_value_form');
        $this->setTitle(__('Value Information'));
    }

    /**
     * Prepare form before rendering HTML
     *
     * @return \Magento\Backend\Block\Widget\Form\Generic
     */
    protected function _prepareForm()
    {
        /** @var $value \Amasty\Finder\Model\Value */
        $value = $this->_coreRegistry->registry('current_amasty_finder_value');
        $finder = $this->_coreRegistry->registry('current_amasty_finder_finder');
        $settingData = [];
        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create(
            [
                'data' => [
                    'id' => 'edit_form',
                    'action' => $this->getUrl('amasty_finder/value/save', [
                        'id' => $this->getRequest()->getParam('id'),
                        'finder_id' => $finder->getId()
                    ]),
                    'method' => 'post',
                    'enctype' => 'multipart/form-data',
                ],
            ]
        );
        $form->setUseContainer(true);
        $this->setForm($form);

        $fieldSet = $form->addFieldset('set', ['legend' => __('General')]);
        $fieldSet->addField('sku', 'text', [
            self::LABEL => __('SKU'),
            self::TITLE => __('SKU'),
            self::NAME => 'sku',
        ]);

        if ($value->getId()) {
            $settingData['sku'] = $this->valueRepository->getSkuById(
                $this->getRequest()->getParam('id'),
                $value->getId()
            );
        }
        $currentId = $value->getId();

        $fields = [];
        while ($currentId) {
            $aliasName = self::NAME . '_' . $currentId;
            $aliasLabel = self::LABEL . '_' . $currentId;

            $model = clone $value;
            $model->load($currentId);
            $currentId = $model->getParentId();
            $dropdownId = $model->getDropdownId();
            $dropdown = $this->dropdownRepository->getById($dropdownId);
            $dropdownName = $dropdown->getName();
            $settingData[$aliasName] = $model->getName();
            $fields[$aliasName] = [
                self::LABEL => __($dropdownName),
                self::TITLE => __($dropdownName),
                self::NAME => $aliasLabel
            ];
        }

        $fields = array_reverse($fields);

        foreach ($fields as $aliasName => $fieldData) {
            $fieldSet->addField($aliasName, 'text', $fieldData);
        }

        if (!$value->getId()) {
            $finder = $value->getFinder();

            foreach ($finder->getDropdowns() as $drop) {
                $aliasName = self::NAME . '_' . $drop->getId();
                $aliasLabel = self::LABEL . '_' . $drop->getId();
                $fieldSet->addField($aliasName, 'text', [
                    self::LABEL => __($drop->getName()),
                    self::TITLE => __($drop->getName()),
                    self::NAME => $aliasLabel
                ]);
            }

            $fieldSet->addField('new_finder', 'hidden', [self::NAME => 'new_finder']);
            $settingData['new_finder'] = 1;
        }

        //set form values
        $form->setValues($settingData);

        return parent::_prepareForm();
    }
}
