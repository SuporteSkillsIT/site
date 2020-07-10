<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Finder
 */

/**
 * Copyright © 2015 Amasty. All rights reserved.
 */
namespace Amasty\Finder\Model;

use Amasty\Finder\Api\Data\FinderInterface;
use Amasty\Finder\Api\MapRepositoryInterface;
use Amasty\Finder\Api\ValueRepositoryInterface;

class Finder extends \Magento\Framework\Model\AbstractModel implements FinderInterface
{
    const SINGLE_PRODUCT = 'single_product';
    /** @var Session */
    private $session;

    /** @var \Magento\Framework\App\Response\RedirectInterface */
    private $redirect;

    /** @var \Magento\Framework\App\Response\Http */
    private $response;

    /** @var MapRepositoryInterface */
    private $mapRepository;

    /** @var \Amasty\Finder\Api\ValueRepositoryInterface */
    private $valueRepository;

    /**
     * @var \Amasty\Finder\Helper\Config
     */
    private $configHelper;

    /**
     * @var \Amasty\Finder\Api\DropdownRepositoryInterface
     */
    private $dropdownRepository;

    /**
     * @var Import
     */
    private $importModel;

    /**
     * @var \Amasty\Finder\Api\FinderRepositoryInterface
     */
    private $finderRepository;

    /**
     * @var \Magento\Framework\App\Request\Http
     */
    private $request;

    /**
     * Finder constructor.
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param Session $session
     * @param \Magento\Framework\App\Response\RedirectInterface $redirect
     * @param \Magento\Framework\App\Response\Http $response
     * @param \Amasty\Finder\Api\FinderRepositoryInterface $finderRepository
     * @param MapRepositoryInterface $mapRepository
     * @param ValueRepositoryInterface $valueRepository
     * @param \Amasty\Finder\Helper\Config $configHelper
     * @param Import $importModel
     * @param \Amasty\Finder\Api\DropdownRepositoryInterface $dropdownRepository
     * @param \Magento\Framework\App\Request\Http $request
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource|null $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb|null $resourceCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Amasty\Finder\Model\Session $session,
        \Magento\Framework\App\Response\RedirectInterface $redirect,
        \Magento\Framework\App\Response\Http $response,
        \Amasty\Finder\Api\FinderRepositoryInterface $finderRepository,
        \Amasty\Finder\Api\MapRepositoryInterface $mapRepository,
        \Amasty\Finder\Api\ValueRepositoryInterface $valueRepository,
        \Amasty\Finder\Helper\Config $configHelper,
        \Amasty\Finder\Model\Import $importModel,
        \Amasty\Finder\Api\DropdownRepositoryInterface $dropdownRepository,
        \Magento\Framework\App\Request\Http $request,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        $this->session = $session;
        $this->redirect = $redirect;
        $this->response = $response;
        $this->mapRepository = $mapRepository;
        $this->valueRepository = $valueRepository;
        $this->configHelper = $configHelper;
        $this->dropdownRepository = $dropdownRepository;
        $this->importModel = $importModel;
        $this->finderRepository = $finderRepository;
        $this->request = $request;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * Constructor
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(\Amasty\Finder\Model\ResourceModel\Finder::class);
        parent::_construct();
    }

    /**
     * @return \Amasty\Finder\Model\ResourceModel\Dropdown\Collection
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getDropdowns()
    {
        return $this->dropdownRepository->getByFinderId($this->getId());
    }

    /**
     * @param $dropdowns
     * @param $categoryId
     * @param $applyUrls
     * @return bool
     */
    public function saveFilter($dropdowns, $categoryId, $applyUrls)
    {
        if (!$dropdowns) {
            return false;
        }

        if (!is_array($dropdowns)) {
            return false;
        }

        $safeValues = [];
        $dropdownId = 0;
        $current = 0;
        foreach ($this->getDropdowns() as $dropdown) {
            $dropdownId = $dropdown->getId();
            $safeValues[$dropdownId] = isset($dropdowns[$dropdownId]) ? $dropdowns[$dropdownId] : 0;
            if (isset($dropdowns[$dropdownId]) && ($dropdowns[$dropdownId])) {
                $current = $dropdowns[$dropdownId];
            }
        }

        if ($dropdownId) {
            $safeValues['last'] = $safeValues[$dropdownId];
            $safeValues['current'] = $current;
        }

        $safeValues['filter_category_id'] = $categoryId;
        $safeValues['apply_url'] = array_unique($applyUrls);
        $safeValues['url_param'] = $this->createUrlParam($safeValues);
        if ($safeValues['url_param']) {
            $this->session->setFinderData($this->getId(), $safeValues);
        }

        return true;
    }

    /**
     * @return bool
     */
    public function resetFilter()
    {
        $this->session->reset($this->getId());
        $this->session->resetSingleProduct();
        return true;
    }

    /**
     * @param \Magento\Catalog\Model\Layer $layer
     * @param $isUniversal
     * @param $isUniversalLast
     * @return bool
     */
    public function applyFilter(\Magento\Catalog\Model\Layer $layer, $isUniversal, $isUniversalLast)
    {
        $id = $this->getSavedValue('current');
        if (!$id) {
            return false;
        }

        if (!$this->isAllowedInCategory($layer->getCurrentCategory()->getId())) {
            return false;
        }

        $finderId = $this->getId();

        $collection = $layer->getProductCollection();
        $cnt = $this->countEmptyDropdowns();

        $this->finderRepository->addConditionToProductCollection(
            $collection,
            $id,
            $cnt,
            $finderId,
            $isUniversal,
            $isUniversalLast
        );

        $isSingleProductRedirect = $this->configHelper->getConfigValue('general/redirect_single_product');

        $cloneCollection = clone $collection;

        if ($this->request->getParam(self::SINGLE_PRODUCT)
            && $isSingleProductRedirect
            && $cloneCollection->getSize() == 1
        ) {
            $url = $collection->getFirstItem()->getProductUrl();
            $collection->clear();
            $this->redirect->redirect($this->response, $url);
            $this->session->setSingleProduct();
        }

        return true;
    }

    /**
     * @param $dropdownId
     * @return int
     */
    public function getSavedValue($dropdownId)
    {
        $values = $this->session->getFinderData($this->getId());

        if (!is_array($values)) {
            return 0;
        }

        if (empty($values[$dropdownId])) {
            return 0;
        }

        return $values[$dropdownId];
    }

    /**
     * @param $file
     * @return array
     */
    public function importUniversal($file)
    {
        return $this->finderRepository->importUniversal($this, $file);
    }

    /**
     * @param $id
     * @return bool
     */
    public function deleteMapRow($id)
    {
        return $this->mapRepository->deleteById($id);
    }

    /**
     * @param $id
     * @return bool
     */
    public function isDeletable($id)
    {
        return $this->finderRepository->isDeletable($id);
    }

    /**
     * @param $id
     * @return bool
     */
    public function newSetterId($id)
    {
        return $id ? $this->mapRepository->getById($id)->getValueId() : false;
    }

    /**
     * @return int
     */
    private function countEmptyDropdowns()
    {
        $num = 0;

        // we assume the values are always exist.
        $values = $this->session->getFinderData($this->getId());
        foreach ($values as $key => $dropdown) {
            if (is_numeric($key) && !$dropdown) {
                $num++;
            }
        }

        return $num;
    }

    /**
     * @param $current
     * @return array
     */
    public function getDropdownsByCurrent($current)
    {
        $dropdowns = [];
        while ($current) {
            $valueModel = $this->valueRepository->getById($current);
            $dropdowns[$valueModel->getDropdownId()] = $current;
            $current = $valueModel->getParentId();
        }

        return $dropdowns;
    }

    /**
     * @return null|string
     */
    public function getUrlParam()
    {
        $values = '';

        if (!$this->session->getAllFindersData()) {
            return null;
        }

        foreach ($this->session->getAllFindersData() as $value) {

            if (!is_array($value)) {
                return null;
            }

            if (empty($value['url_param'])) {
                return null;
            }

            $values .= !$values ? $value['url_param'] : '&' . $value['url_param'];
        }
        return $values;
    }

    /**
     * For current finder creates his description for URL
     *
     * @return string like year-make-model-number.html
     */
    private function createUrlParam($values)
    {
        $sep = $this->configHelper->getConfigValue('general/separator');
        $suffix = $this->configHelper->getConfigValue('general/suffix');

        $urlParam = '';

        foreach ($values as $key => $value) {
            if ('current' == $key) {
                $urlParam .= $value . $suffix;
                break;
            }

            if (!empty($value) && is_numeric($key)) {
                $valueModel = $this->valueRepository->getById($value);
                if ($valueModel->getId()) {
                    $urlParam .= strtolower(preg_replace('/[^\da-zA-Z]/', '-', $valueModel->getName())) . $sep;
                }
            }
        }
        if (empty($urlParam)) {
            $urlParam = null;
        }

        return $urlParam;
    }

    /**
     *  Get last `number` part from the year-make-model-number.html string
     *
     * @param string $param like year-make-model-number.html
     *
     * @return string like number
     */
    public function parseUrlParam($param)
    {
        $sep = $this->configHelper->getConfigValue('general/separator');
        $suffix = $this->configHelper->getConfigValue('general/suffix');

        $param = explode($sep, $param);
        $param = str_replace($suffix, '', $param[count($param) - 1]);

        return $param;
    }

    /**
     * @param $url
     * @param $name
     * @return string
     */
    public function removeGet($url, $name)
    {
        $url = str_replace("&amp;", "&", $url);
        list($urlPart, $qsPart) = array_pad(explode("?", $url), 2, "");
        parse_str($qsPart, $qsVars);

        if ($qsVars) {
            $findParams = explode('&', $qsVars[$name]);
            foreach ($findParams as $key => $item) {
                if (strpos($this->getUrlParam(), $item) === false) {
                    unset($findParams[$key]);
                }
            }

            if ($findParams) {
                $qsVars[$name] = join('&', $findParams);
            } else {
                unset($qsVars[$name]);
            }
        }

        $url = count($qsVars) > 0 ? $urlPart . "?" . http_build_query($qsVars) : $urlPart;

        return $url;
    }

    /**
     * @return int
     */
    private function getInitialCategoryId()
    {
        $value = $this->session->getFinderData($this->getId());

        return isset($value['filter_category_id']) ? $value['filter_category_id'] : 0;
    }

    /**
     * @param $currentCategoryId
     * @return bool
     */
    private function isAllowedInCategory($currentCategoryId)
    {
        $res = $this->configHelper->getConfigValue('general/category_search');
        if (!$res) {
            return true;
        }

        if (!$this->getInitialCategoryId()) {
            return true;
        }

        return ($this->getInitialCategoryId() == $currentCategoryId);
    }

    /**
     * @return \Magento\Framework\Model\AbstractModel
     */
    public function afterDelete()
    {
        $this->importModel->afterDeleteFinder($this->getId());
        return parent::afterDelete();
    }

    /**
     * @return int
     */
    public function getFinderId()
    {
        return $this->_getData(FinderInterface::FINDER_ID);
    }

    /**
     * @param int $finderId
     * @return $this
     */
    public function setFinderId($finderId)
    {
        $this->setData(FinderInterface::FINDER_ID, $finderId);

        return $this;
    }

    /**
     * @return int
     */
    public function getCnt()
    {
        return $this->_getData(FinderInterface::CNT);
    }

    /**
     * @param int $cnt
     * @return $this
     */
    public function setCnt($cnt)
    {
        $this->setData(FinderInterface::CNT, $cnt);

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->_getData(FinderInterface::NAME);
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->setData(FinderInterface::NAME, $name);

        return $this;
    }

    /**
     * @return string
     */
    public function getTemplate()
    {
        return $this->_getData(FinderInterface::TEMPLATE);
    }

    /**
     * @param string $template
     * @return $this
     */
    public function setTemplate($template)
    {
        $this->setData(FinderInterface::TEMPLATE, $template);

        return $this;
    }

    /**
     * @return string
     */
    public function getMetaTitle()
    {
        return $this->_getData(FinderInterface::META_TITLE);
    }

    /**
     * @param string $metaTitle
     * @return $this
     */
    public function setMetaTitle($metaTitle)
    {
        $this->setData(FinderInterface::META_TITLE, $metaTitle);

        return $this;
    }

    /**
     * @return string
     */
    public function getMetaDescr()
    {
        return $this->_getData(FinderInterface::META_DESCR);
    }

    /**
     * @param string $metaDescr
     * @return $this
     */
    public function setMetaDescr($metaDescr)
    {
        $this->setData(FinderInterface::META_DESCR, $metaDescr);

        return $this;
    }

    /**
     * @return string
     */
    public function getCustomUrl()
    {
        return $this->_getData(FinderInterface::CUSTOM_URL);
    }

    /**
     * @param string $customUrl
     * @return $this
     */
    public function setCustomUrl($customUrl)
    {
        $this->setData(FinderInterface::CUSTOM_URL, $customUrl);

        return $this;
    }
}
