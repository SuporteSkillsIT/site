<?php
/**
 * Mirasvit
 *
 * This source file is subject to the Mirasvit Software License, which is available at http://mirasvit.com/license/.
 * Do not edit or add to this file if you wish to upgrade the to newer versions in the future.
 * If you wish to customize this module for your needs, please refer to http://www.magentocommerce.com for more information.
 *
 * @copyright Copyright (C) 2018 Mirasvit (http://mirasvit.com/)
 */

namespace Mirasvit\Dashboard\Controller\Adminhtml\Api;

use Magento\Backend\App\Action;
use Mirasvit\Dashboard\Api\Data\BoardInterface;
use Mirasvit\Dashboard\Model\Block;
use Mirasvit\Dashboard\Repository\BoardRepository;
use Mirasvit\Dashboard\Service\BlockService;
use Mirasvit\Report\Api\Service\CastingServiceInterface;
use Mirasvit\Report\Controller\Adminhtml\Api\AbstractApi;

class Request extends AbstractApi
{
    private $boardRepository;

    private $castingService;

    private $blockService;

    public function __construct(
        BoardRepository $boardRepository,
        CastingServiceInterface $castingService,
        BlockService $blockService,
        Action\Context $context
    ) {
        $this->boardRepository = $boardRepository;
        $this->castingService  = $castingService;
        $this->blockService    = $blockService;

        parent::__construct($context);
    }

    public function dispatch(\Magento\Framework\App\RequestInterface $request)
    {
        $token = $this->getRequest()->getParam('token');

        /** @var BoardInterface $board */
        $board = $this->boardRepository->getCollection()
            ->addFieldToFilter(BoardInterface::MOBILE_TOKEN, $token)
            ->addFieldToFilter(BoardInterface::IS_MOBILE_ENABLED, true)
            ->getFirstItem();

        if ($board->getId()) {
            /** @var \Magento\Framework\App\Request\Http $request */
            $request->setDispatched(true);
            $request->setActionName('request');
        }

        return parent::dispatch($request);
    }

    public function execute()
    {
        /** @var \Magento\Framework\App\Response\Http $jsonResponse */
        $jsonResponse = $this->getResponse();

        try {
            $params = $this->castingService->toUnderscore($this->getRequest()->getParams());

            $block = new Block($params['block']);

            $response = $this->blockService->getApiResponse($block, $params['filters']);

            # 2020-09-25 Dmitry Fedyuk https://www.upwork.com/fl/mage2pro
			# «Call to a member function toArray() on null
			# in vendor/mirasvit/module-dashboard/Controller/Adminhtml/Api/Request.php:74»:
			# https://github.com/dxmoto/site/issues/98
			# 2020-09-26 https://github.com/dxmoto/site/issues/98#issuecomment-699480845
			if (!$response) {
				$r = $jsonResponse->representJson(\Zend_Json::encode(['success' => false, 'message' => 'An empty response']));
			}
			else {
				$responseData = $response->toArray();
				$r = $jsonResponse->representJson(\Zend_Json::encode([
					'success' => true,
					'data'    => $responseData,
				]));
			}
			return $r;
        } catch (\Exception $e) {
            return $jsonResponse->representJson(\Zend_Json::encode([
                'success' => false,
                'message' => $e->getMessage(),
            ]));
        }
    }

    public function _isAllowed()
    {
        return true;
    }
}