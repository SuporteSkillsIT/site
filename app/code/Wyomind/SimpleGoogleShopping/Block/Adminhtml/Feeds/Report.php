<?php
/**
 * Copyright © 2019 Wyomind. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Wyomind\SimpleGoogleShopping\Block\Adminhtml\Feeds;

/**
 * Report block
 */
class Report extends \Magento\Backend\Block\Template
{
    /**
     * @var \Wyomind\Framework\Helper\License|null
     */
    protected $licenseHelper = null;

    /**
     * @var \Wyomind\SimpleGoogleShopping\Model\Feeds|null
     */
    protected $sgsModel = null;

    /**
     * @var \Wyomind\SimpleGoogleShopping\Helper\Data|null
     */
    protected $sgsHelper = null;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Wyomind\Framework\Helper\License $licenseHelper
     * @param \Wyomind\SimpleGoogleShopping\Model\Feeds $sgsModel
     * @param \Wyomind\SimpleGoogleShopping\Helper\Data $sgsHelper
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Wyomind\Framework\Helper\License $licenseHelper,
        \Wyomind\SimpleGoogleShopping\Model\Feeds $sgsModel,
        \Wyomind\SimpleGoogleShopping\Helper\Data $sgsHelper,
        array $data = []
    )
    {
        $this->licenseHelper = $licenseHelper;
        $this->sgsModel = $sgsModel;
        $this->sgsHelper = $sgsHelper;
        parent::__construct($context, $data);
    }

    /**
     * Get block content
     * @return string
     */
    public function getContent()
    {
        $request = $this->getRequest();
        $id = $request->getParam('simplegoogleshopping_id');

        if ($id != 0) {
            $this->sgsModel->load($id);
            $this->sgsModel->limit = $this->licenseHelper->getStoreConfig('simplegoogleshopping/system/preview');
            $this->sgsModel->setDisplay(false);
            return $this->sgsHelper->reportToHtml(unserialize($this->sgsModel->getSimplegoogleshoppingReport()));
        }

        return '';
    }
}