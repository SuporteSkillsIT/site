<?php
/**
 * Copyright © 2020 Wyomind. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Wyomind\SimpleGoogleShopping\Block\Adminhtml\Feeds;

/**
 * Preview block
 */
class Preview extends \Magento\Backend\Block\Template
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
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Wyomind\SimpleGoogleShopping\Model\Feeds $sgsModel
     * @param \Wyomind\Framework\Helper\License $licenseHelper
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Wyomind\Framework\Helper\License $licenseHelper,
        \Wyomind\SimpleGoogleShopping\Model\Feeds $sgsModel,
        array $data = []
    )
    {
        $this->licenseHelper = $licenseHelper;
        $this->sgsModel = $sgsModel;
        parent::__construct($context, $data);
    }

    /**
     * Get content of the block
     * @return string
     * @throws \Exception
     */
    public function getContent()
    {
        $request = $this->getRequest();
        $id = $request->getParam('simplegoogleshopping_id');

        if ($id != 0) {
            try {
                $this->sgsModel->load($id);
                $this->sgsModel->limit = $this->licenseHelper->getStoreConfig('simplegoogleshopping/system/preview');
                $this->sgsModel->setDisplay(true);
                return $this->sgsModel->generateXml($request);
            } catch (\Exception $e) {
                return __('Unable to generate the data feed : ' . $e->getMessage());
            }
        }

        return '';
    }
}