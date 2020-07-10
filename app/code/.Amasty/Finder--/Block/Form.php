<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Finder
 */

/**
 * Copyright © 2015 Amasty. All rights reserved.
 */

namespace Amasty\Finder\Block;

class Form extends \Magento\Framework\View\Element\Template
{
    const SIZE_FOR_BUTTONS = 1;

    const HORIZONTAL = 'horizontal';

    /** @var bool */
    private $isApplied = false;

    /** @var \Amasty\Finder\Model\Finder */
    private $finder;

    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    private $coreRegistry;

    /** @var \Magento\Framework\Json\EncoderInterface */
    private $jsonEncoder;

    /** @var \Magento\Catalog\Model\Layer */
    private $catalogLayer;

    /** @var \Magento\Framework\Url\Encoder */
    private $urlEncoder;

    /** @var int */
    private $parentDropdownId = 0;

    /**
     * @var \Amasty\Finder\Api\FinderRepositoryInterface
     */
    private $finderRepository;

    /**
     * @var \Amasty\Finder\Helper\Config
     */
    private $configHelper;

    /**
     * Form constructor.
     * @param \Magento\Framework\App\Request\Http $request
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Json\EncoderInterface $jsonEncoder
     * @param \Magento\Catalog\Model\Layer\Resolver $layerResolver
     * @param \Magento\Framework\Url\Encoder $urlEncoder
     * @param \Amasty\Finder\Api\FinderRepositoryInterface $finderRepository
     * @param \Amasty\Finder\Helper\Config $configHelper
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\App\Request\Http $request,
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Json\EncoderInterface $jsonEncoder,
        \Magento\Catalog\Model\Layer\Resolver $layerResolver,
        \Magento\Framework\Url\Encoder $urlEncoder,
        \Amasty\Finder\Api\FinderRepositoryInterface $finderRepository,
        \Amasty\Finder\Helper\Config $configHelper,
        array $data = []
    ) {
        $this->coreRegistry = $registry;
        $this->jsonEncoder = $jsonEncoder;
        $this->catalogLayer = $layerResolver->get();
        $this->urlEncoder = $urlEncoder;
        $this->request = $request;
        $this->finderRepository = $finderRepository;
        $this->configHelper = $configHelper;
        parent::__construct($context, $data);
        $this->apply();
    }

    /** @return \Amasty\Finder\Model\Finder */
    public function getFinder()
    {
        if ($this->finder === null) {
            $this->finder = $this->finderRepository->getById($this->getId());
        }
        return $this->finder;
    }

    /**
     * @param \Amasty\Finder\Model\Dropdown $dropdown
     *
     * @return string
     */
    public function getDropdownAttributes(\Amasty\Finder\Model\Dropdown $dropdown)
    {
        $html = sprintf(
            'name="finder[%s]" id="finder-%s--%s" data-dropdown-id="%s"',
            $dropdown->getId(),
            $this->getId(),
            $dropdown->getId(),
            $dropdown->getId()
        );

        $parentValueId = $this->getFinder()->getSavedValue($this->parentDropdownId);
        $currentValueId = $this->getFinder()->getSavedValue($dropdown->getId());

        if ($this->isHidden($dropdown) && (!$parentValueId) && (!$currentValueId)) {
            $html .= 'disabled="disabled"';
        }

        return $html;
    }

    /**
     * @param \Amasty\Finder\Model\Dropdown $dropdown
     *
     * @return array
     */
    public function getDropdownValues(\Amasty\Finder\Model\Dropdown $dropdown)
    {
        $values = [];

        $parentValueId = $this->getFinder()->getSavedValue($this->parentDropdownId);
        $currentValueId = $this->getFinder()->getSavedValue($dropdown->getId());

        if ($this->isHidden($dropdown) && (!$parentValueId) && (!$currentValueId)) {
            return $values;
        }
        if (!$this->paramsExist()) {
            $currentValueId = 0;
        }

        $values = $dropdown->getValues($parentValueId, $currentValueId);

        $this->parentDropdownId = $dropdown->getId();

        return $values;
    }

    /**
     * @return bool
     */
    public function isButtonsVisible()
    {
        $cnt = count($this->getFinder()->getDropdowns());

        // we have just 1 dropdown. show the button
        if (self::SIZE_FOR_BUTTONS == $cnt) {
            return true;
        }

        $partialSearch = !!$this->configHelper->getConfigValue('general/partial_search');

        // at least one value is selected and we allow partial search
        if ($this->getFinder()->getSavedValue('current') && $partialSearch) {
            return true;
        }

        // all values are selected.
        if (($this->getFinder()->getSavedValue('last'))) {
            return true;
        }

        return false;
    }

    /**
     * @param \Amasty\Finder\Model\Dropdown $dropdown
     * @return bool
     */
    private function isHidden(\Amasty\Finder\Model\Dropdown $dropdown)
    {
        //it's not the first dropdown && value is not selected
        return ($dropdown->getPos() && !$this->getFinder()->getSavedValue($dropdown->getId()));
    }

    /**
     * @return string
     */
    private function getAjaxUrl()
    {
        $isCurrentlySecure = $this->_storeManager->getStore()->isCurrentlySecure();
        $secure = $isCurrentlySecure ? true : false ;
        $url = $this->getUrl('amfinder/index/options', ['_secure' => $secure]);

        return $url;
    }

    /**
     * @return string
     */
    public function getBackUrl()
    {
        $securedFlag = $this->_storeManager->getStore()->isCurrentlySecure();
        $secured = ['_secure' => $securedFlag];

        $url = $this->getCustomUrl($secured) ?: $this->getUrl('amfinder', $secured);

        $category = $this->coreRegistry->registry('current_category');

        if (!$category) {
            return $this->formatUrl($url);
        }

        if ($this->coreRegistry->registry('current_product')) {
            return $this->formatUrl($url);
        }

        if ($category->getDisplayMode() == \Magento\Catalog\Model\Category::DM_PAGE) {
            return $this->formatUrl($url);
        }

        $url = $this->_urlBuilder->getCurrentUrl();

        return $this->formatUrl($url);
    }

    /**
     * @param $secured
     * @return bool|string
     */
    private function getCustomUrl($secured)
    {
        $customUrl = $this->getFinder()->getCustomUrl()
            ?: $this->configHelper->getConfigValue('general/custom_category');

        if ($customUrl) {
            $url = $this->_urlBuilder->getCurrentUrl();

            if (strpos($url, $customUrl) === false) {
                $url = $this->getUrl($customUrl, $secured);
            }
        } else {
            $url = false;
        }

        return $url;
    }

    /**
     * @return string
     */
    public function getResetUrl()
    {
        if ($this->configHelper->getConfigValue('general/reset_home') == 'current' ||
            $this->request->getFullActionName() == 'cms_index_index'
        ) {
            return $this->formatUrl($this->_urlBuilder->getCurrentUrl());
        } else {
            return $this->getBackUrl();
        }
    }

    /**
     * @return string
     */
    public function getActionUrl()
    {
        $securedFlag = $this->_storeManager->getStore()->isCurrentlySecure();
        $url = $this->getUrl('amfinder/index/search', ['_secure' => $securedFlag]);
        return $url;
    }

    /**
     * @return \Magento\Framework\Phrase|string
     */
    protected function _toHtml()
    {
        $finderId = $this->getId();
        if (!$finderId) {
            return __('Please specify the Parts Finder ID');
        }

        $finder = $this->getFinder();
        if (!$finder->getId()) {
            return __('Please specify an exiting Parts Finder ID');
        }

        if (!$this->coreRegistry->registry($finderId)) {
            $this->coreRegistry->register($finderId, true);
        } else {
            return false;
        }

        $this->setLocation($this->getLocation() . $this->coreRegistry->registry('cms_amfinder'));

        return parent::_toHtml();
    }

    /**
     * @return $this
     */
    private function apply()
    {
        if ($this->isApplied) {
            return $this;
        }

        $this->_template = 'amfinder.phtml';

        $this->isApplied = true;

        $finder = $this->getFinder();
        $urlParam = $this->getRequest()->getParam('find');

        // XSS disabling
        $filter = ["<", ">"];
        $urlParam = str_replace($filter, "|", $urlParam);
        $urlParam = htmlspecialchars($urlParam);

        if ($urlParam) {
            $urlParam = $finder->parseUrlParam($urlParam);
            $current = $finder->getSavedValue('current');

            if ($urlParam && ($current != $urlParam)) {
                // url has higher priority than session
                $dropdowns = $finder->getDropdownsByCurrent($urlParam);
                $finder->saveFilter($dropdowns, $this->getCurrentCategoryId(), [$this->getCurrentApplyUrl()]);
            }
        }

        $isUniversal = (bool) $this->configHelper->getConfigValue('general/universal');
        $isUniversalLast = (bool) $this->configHelper->getConfigValue('general/universal_last');

        if ($this->paramsExist()) {
            $finder->applyFilter($this->catalogLayer, $isUniversal, $isUniversalLast);
        }

        return $this;
    }

    /**
     * @return bool
     */
    private function paramsExist()
    {
        return strpos($this->_urlBuilder->getCurrentUrl(), 'find=') !== false;
    }

    /**
     * @param $url
     * @return string
     */
    private function formatUrl($url)
    {
        if ($this->_storeManager->getStore()->isCurrentlySecure()) {
            $url = str_replace("http://", "https://", $url);
        }

        return $this->urlEncoder->encode($url);
    }

    /**
     * @return int
     */
    public function getCurrentCategoryId()
    {
        return $this->catalogLayer->getCurrentCategory()->getId();
    }

    /**
     * @return string
     */
    public function getJsonConfig()
    {
        return $this->jsonEncoder->encode([
            'ajaxUrl' => $this->getAjaxUrl(),
            'isNeedLast' => (int) $this->configHelper->getConfigValue('general/partial_search'),
            'autoSubmit' => (int) $this->configHelper->getConfigValue('general/auto_submit')
        ]);
    }

    /**
     * @return array|string
     */
    private function getCurrentApplyUrl()
    {
        $currentUrl = $this->_urlBuilder->getCurrentUrl();
        $currentUrl = explode('?', $currentUrl);
        $currentUrl = array_shift($currentUrl);
        return $currentUrl;
    }

    /**
     * @return string
     */
    public function getCurrentApplyUrlEncoded()
    {
        $currentUrl = $this->getCurrentApplyUrl();
        return $this->urlEncoder->encode($currentUrl);
    }

    /**
     * @return float|string
     */
    public function getDropdownWidth()
    {
        $finder = $this->getFinder();
        return $finder->getTemplate() == self::HORIZONTAL
            ? floor(100 / count($finder->getDropdowns()) - self::SIZE_FOR_BUTTONS) : '';
    }
}
