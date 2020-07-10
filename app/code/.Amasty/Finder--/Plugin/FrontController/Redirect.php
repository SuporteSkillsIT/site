<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Finder
 */


namespace Amasty\Finder\Plugin\FrontController;

use Amasty\Finder\Controller\Index\Search;

class Redirect
{
    /** @var \Amasty\Finder\Model\Session */
    private $session;

    /** @var \Amasty\Finder\Helper\Url */
    private $urlHelper;

    /** @var \Magento\Framework\App\Response\Http */
    private $response;

    /**
     * Redirect constructor.
     * @param \Amasty\Finder\Model\Session $session
     * @param \Amasty\Finder\Helper\Url $urlHelper
     * @param \Magento\Framework\App\Response\Http $response
     */
    public function __construct(
        \Amasty\Finder\Model\Session $session,
        \Amasty\Finder\Helper\Url $urlHelper,
        \Magento\Framework\App\Response\Http $response
    ) {
        $this->session = $session;
        $this->urlHelper = $urlHelper;
        $this->response = $response;
    }

    /**
     * @param \Magento\Framework\App\FrontControllerInterface $subject
     * @param \Closure $proceed
     * @param \Magento\Framework\App\RequestInterface $request
     * @return \Magento\Framework\App\Response\HttpInterface|mixed
     */
    public function aroundDispatch(
        \Magento\Framework\App\FrontControllerInterface $subject,
        \Closure $proceed,
        \Magento\Framework\App\RequestInterface $request
    ) {
        $savedFinders = $this->session->getAllFindersData();
        if (strpos($request->getRequestUri(), Search::SINGLE_PRODUCT_FLAG) !== false) {
            $request->setParams([\Amasty\Finder\Model\Finder::SINGLE_PRODUCT => 1]);
            $request->setRequestUri(str_replace(Search::SINGLE_PRODUCT_FLAG, '', $request->getRequestUri()));
        }
        /** @var \Magento\Framework\App\Request\Http $request */
        if (is_array($savedFinders)) {
            $baseUrl = rtrim($request->getDistroBaseUrl(), '/');
            $currentUrlWithoutGet = $baseUrl . $request->getRequestString();
            foreach ($savedFinders as $finder) {
                if (!is_array($finder)) {
                    continue;
                }
                if (in_array($currentUrlWithoutGet, $finder['apply_url']) &&
                    strpos($request->getRequestUri(), $finder['url_param']) === false &&
                    !$this->urlHelper->hasFinderParamInUri($request->getRequestUri())
                ) {
                    $redirectUrl = $this->urlHelper->getUrlWithFinderParam(
                        $request->getOriginalPathInfo(),
                        $finder['url_param']
                    );
                    $redirectUrl = $baseUrl . $redirectUrl;

                    return $this->response
                        ->setHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0')
                        ->setRedirect($redirectUrl);
                }
            }
        }

        return $proceed($request);
    }
}
