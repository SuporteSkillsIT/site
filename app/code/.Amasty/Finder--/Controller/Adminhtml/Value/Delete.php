<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Finder
 */

/**
 * Copyright © 2015 Amasty. All rights reserved.
 */
namespace Amasty\Finder\Controller\Adminhtml\Value;

class Delete extends \Amasty\Finder\Controller\Adminhtml\Value
{
    /**
     * Dispatch request
     * @throws \Magento\Framework\Exception\NotFoundException
     */
    public function execute()
    {
        $this->_initModel();
        $setterId = $this->getRequest()->getParam('id');
        if ($setterId) {
            try {
                $newId = $this->model->newSetterId($setterId);
                $this->model->deleteMapRow($setterId);

                $currentId = $newId;
                while (($currentId) && ($this->model->isDeletable($currentId))) {
                    $value = $this->valueRepository->getById($currentId);
                    $currentId = $value->getParentId();
                    $this->valueRepository->deleteById($value->getId());
                }

                $this->messageManager->addSuccess(__('You deleted the item.'));
                $this->_redirect('amasty_finder/finder/edit', ['id'=>$this->model->getId()]);
                return;
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addError(
                    __('We can\'t delete item right now. Please review the log and try again.')
                );
                $this->logger->critical($e);
                $this->_redirect('amasty_finder/finder/edit', ['id' => $this->model->getId()]);
                return;
            }
        }
        $this->messageManager->addError(__('We can\'t find a item to delete.'));
        $this->_redirect('amasty_finder/finder/edit', ['id'=>$this->model->getId()]);
    }
}
