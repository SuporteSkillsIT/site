<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Finder
 */


namespace Amasty\Finder\Observer;

class LoadPage implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * @var \Amasty\Finder\Model\Session
     */
    private $session;

    /**
     * @var \Magento\Framework\App\Response\Http
     */
    private $response;

    /**
     * @var \Magento\Framework\App\Request\Http
     */
    private $request;

    /**
     * @var \Amasty\Finder\Helper\Url
     */
    private $urlHelper;

    /**
     * @var \Magento\Framework\UrlInterface
     */
    private $urlInterface;

    /**
     * @var \Amasty\Finder\Helper\Config
     */
    private $configHelper;

    /**
     * LoadPage constructor.
     * @param \Amasty\Finder\Model\Session $session
     * @param \Magento\Framework\App\Request\Http $request
     * @param \Magento\Framework\App\Response\Http $response
     * @param \Amasty\Finder\Helper\Url $urlHelper
     * @param \Magento\Framework\UrlInterface $urlInterface
     * @param \Amasty\Finder\Helper\Config $configHelper
     */
    public function __construct(
        \Amasty\Finder\Model\Session $session,
        \Magento\Framework\App\Request\Http $request,
        \Magento\Framework\App\Response\Http $response,
        \Amasty\Finder\Helper\Url $urlHelper,
        \Magento\Framework\UrlInterface $urlInterface,
        \Amasty\Finder\Helper\Config $configHelper
    ) {
        $this->session = $session;
        $this->request = $request;
        $this->response = $response;
        $this->urlHelper = $urlHelper;
        $this->urlInterface = $urlInterface;
        $this->configHelper = $configHelper;
    }

    /**
     * @param \Magento\Framework\Event\Observer $observer
     * @return \Magento\Framework\Event\Observer
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        if ($this->configHelper->getConfigValue('general/category_search')) {
            return $observer;
        }

        $html = $observer->getResponse()->getContent();
        $request = $observer->getRequest();
        $activeFinders = $this->session->getAmfinderSavedValues()
            ?: $this->session->getAllFindersData();

        if (!$activeFinders || $request->getParam('find') !== null) {
            return $observer;
        }

        foreach ($activeFinders as $finderId => $values) {
            $finderExist = strpos($html, 'amfinder_' . $finderId) !== false;

            if ($finderExist) {
                $observer->getResponse()->setRedirect($this->urlHelper->getUrlWithFinderParam(
                    $this->urlInterface->getCurrentUrl(),
                    $values['url_param']
                ));
                break;
            }
        }

        return $observer;
    }
}
