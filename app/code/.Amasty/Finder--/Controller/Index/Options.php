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

use Amasty\Finder\Api\DropdownRepositoryInterface;
use Magento\Framework\App\Action\Context;

class Options extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Framework\Json\EncoderInterface
     */
    private $jsonEncoder;

    /**
     * @var DropdownRepositoryInterface
     */
    private $dropdownRepository;

    /**
     * Options constructor.
     * @param Context $context
     * @param DropdownRepositoryInterface $dropdownRepository
     * @param \Magento\Framework\Json\EncoderInterface $jsonEncoder
     */
    public function __construct(
        Context $context,
        \Amasty\Finder\Api\DropdownRepositoryInterface $dropdownRepository,
        \Magento\Framework\Json\EncoderInterface $jsonEncoder
    ) {
        $this->jsonEncoder = $jsonEncoder;
        $this->dropdownRepository = $dropdownRepository;
        parent::__construct($context);
    }

    /**
     * Dispatch request
     *
     * @return \Magento\Framework\Controller\ResultInterface|ResponseInterface
     * @throws \Magento\Framework\Exception\NotFoundException
     */
    public function execute()
    {
        $parentId = $this->getRequest()->getParam('parent_id', false);
        $dropdownId = $this->getRequest()->getParam('dropdown_id');
        $useSavedValues = $this->getRequest()->getParam('use_saved_values', "false");
        $options = [];

        if ($parentId !== false && $dropdownId) {
            /** @var \Amasty\Finder\Model\Dropdown $dropdown */
            $dropdown = $this->dropdownRepository->getById($dropdownId);
            $selectedValue = 0;
            if ($useSavedValues === "true") {
                $selectedValue = $dropdown->getFinder()->getSavedValue($dropdown->getId());
            }
            $options = $dropdown->getValues($parentId, $selectedValue);

            if (count($options) == 2) {
                $options[1]['selected'] = true;
            }
        }

        $response = $this->jsonEncoder->encode($options);
        return $this->getResponse()->setBody($response);
    }
}
