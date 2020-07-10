<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Finder
 */

/**
 * Copyright © 2015 Amasty. All rights reserved.
 */

namespace Amasty\Finder\Controller\Index;

use Magento\Framework\App\Action\Context;

class Search extends \Magento\Framework\App\Action\Action
{
    const RESET = 'reset';
    const SINGLE_PRODUCT_FLAG = '?amfinder';

    /** @var \Magento\Framework\Url\Decoder */
    private $urlDecoder;

    /** @var \Amasty\Finder\Helper\Url */
    private $urlHelper;

    /**
     * @var \Amasty\Finder\Helper\Config
     */
    private $configHelper;

    /**
     * @var \Amasty\Finder\Api\FinderRepositoryInterface
     */
    private $finderRepository;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;

    /**
     * Search constructor.
     * @param Context $context
     * @param \Magento\Framework\Url\Decoder $urlDecoder
     * @param \Amasty\Finder\Helper\Url $urlHelper
     * @param \Amasty\Finder\Helper\Config $configHelper
     * @param \Amasty\Finder\Api\FinderRepositoryInterface $finderRepository
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     */
    public function __construct(
        Context $context,
        \Magento\Framework\Url\Decoder $urlDecoder,
        \Amasty\Finder\Helper\Url $urlHelper,
        \Amasty\Finder\Helper\Config $configHelper,
        \Amasty\Finder\Api\FinderRepositoryInterface $finderRepository,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    ) {
        $this->urlDecoder = $urlDecoder;
        $this->urlHelper = $urlHelper;
        $this->configHelper = $configHelper;
        $this->finderRepository = $finderRepository;
        $this->storeManager = $storeManager;
        parent::__construct($context);
    }

    public function execute()
    {
        $finderId = $this->getRequest()->getParam('finder_id');
        /** @var \Amasty\Finder\Model\Finder $finder */
        $finder = $this->finderRepository->getById($finderId);
        $backUrl = $this->urlDecoder->decode($this->getRequest()->getParam('back_url'));
        $currentApplyUrl = $this->urlDecoder->decode($this->getRequest()->getParam('current_apply_url'));

        $baseBackUrl = explode('?', $backUrl);
        $baseBackUrl = array_shift($baseBackUrl);

        $dropdowns = $this->getRequest()->getParam('finder');
        if ($dropdowns) {
            $finder->saveFilter(
                $dropdowns,
                $this->getRequest()->getParam('category_id'),
                [$currentApplyUrl, $baseBackUrl]
            );
        }

        $backUrl = $this->urlHelper->getUrlWithFinderParam($backUrl, $finder->getUrlParam())
            . self::SINGLE_PRODUCT_FLAG;

        if ($this->configHelper->getConfigValue('general/clear_other_conditions')) {
            $finders = $this->finderRepository->getWithoutId($finder->getId());
            foreach ($finders as $finder) {
                $finder->resetFilter();
            }
        }

        if ($this->getRequest()->getParam(self::RESET)) {
            $finder->resetFilter();
            $resetConfig = $this->configHelper->getConfigValue('general/reset_home');

            if ($resetConfig == \Amasty\Finder\Model\Source\Reset::VALUE_HOME) {
                $backUrl = $this->storeManager->getStore()->getBaseUrl();
            } else {
                $resetUrl = $this->urlDecoder->decode($this->getRequest()->getParam('reset_url'));
                $backUrl = $finder->removeGet($resetUrl, \Amasty\Finder\Helper\Url::FINDER_URL_PARAM);
            }
        }

        $this->getResponse()->setRedirect($backUrl);
    }
}
