<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Finder
 */

/**
 * Copyright © 2015 Amasty. All rights reserved.
 */

namespace Amasty\Finder\Block\Adminhtml\Finder\Edit\Tab\Products\Grid;

class ColumnSet extends \Magento\Backend\Block\Widget\Grid\ColumnSet
{
    /**
     * Core registry
     *
     * @var \Amasty\Finder\Model\Finder $finder
     */
    private $finder;

    /**
     * ColumnSet constructor.
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Backend\Model\Widget\Grid\Row\UrlGeneratorFactory $generatorFactory
     * @param \Magento\Backend\Model\Widget\Grid\SubTotals $subtotals
     * @param \Magento\Backend\Model\Widget\Grid\Totals $totals
     * @param \Magento\Framework\Registry $registry
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Backend\Model\Widget\Grid\Row\UrlGeneratorFactory $generatorFactory,
        \Magento\Backend\Model\Widget\Grid\SubTotals $subtotals,
        \Magento\Backend\Model\Widget\Grid\Totals $totals,
        \Magento\Framework\Registry $registry,
        array $data
    ) {
        /** @var \Amasty\Finder\Model\Finder $finder */
        $this->finder = $registry->registry('current_amasty_finder_finder');
        parent::__construct($context, $generatorFactory, $subtotals, $totals, $data);
    }

    /**
     * @return \Magento\Backend\Block\Widget\Grid\ColumnSet
     */
    protected function _prepareLayout()
    {
        foreach ($this->finder->getDropdowns() as $dropdown) {
            $dropdownPosition = $dropdown->getPos();
            $this->addColumn(
                "name" . $dropdownPosition,
                [
                    'header' => $dropdown->getName(),
                    'index' => "name" . $dropdownPosition,
                    'filter_index' => "d" . $dropdownPosition . ".name",
                ]
            );
        }

        $this->addColumn('sku', ['header' => __('SKU'), 'index' => 'sku']);

        $this->addColumn('action', [
            'header' => __('Action'),
            'width' => '50px',
            'type' => 'action',
            'getter' => 'getVid',
            'actions' => [
                [
                    'caption' => __('Delete'),
                    'url' => [
                        'base' => 'amasty_finder/value/delete',
                        'params' => ['finder_id' => $this->finder->getId()]
                    ],
                    'field' => 'id',
                    'confirm' => __('Are you sure?')
                ]
            ],
            'filter' => false,
            'sortable' => false,
            'is_system' => true,
        ]);
        return parent::_prepareLayout();
    }

    /**
     * @param $title
     * @param $data
     */
    private function addColumn($title, $data)
    {
        $column = $this->getLayout()
            ->createBlock(\Magento\Backend\Block\Widget\Grid\Column::class, $title)
            ->addData($data);
        $this->setChild($title, $column);
    }
}
