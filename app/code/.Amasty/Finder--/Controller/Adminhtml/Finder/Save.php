<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Finder
 */

/**
 * Copyright © 2015 Amasty. All rights reserved.
 */

namespace Amasty\Finder\Controller\Adminhtml\Finder;

class Save extends \Amasty\Finder\Controller\Adminhtml\Finder
{
    /**
     * Dispatch request
     * @throws \Magento\Framework\Exception\NotFoundException
     */
    public function execute()
    {
        if ($this->getRequest()->getPostValue()) {
            try {

                $model = $this->finderRepository->getFinderModel();
                $data = $this->getRequest()->getPostValue();
                $inputFilter = new \Zend_Filter_Input([], [], $data);
                $data = $inputFilter->getUnescaped();
                $finderId = $this->getRequest()->getParam('id');
                if ($finderId) {
                    $model = $this->finderRepository->getById($finderId);
                    if ($finderId != $model->getId()) {
                        throw new \Magento\Framework\Exception\LocalizedException(__('The wrong item is specified.'));
                    }
                }
                $model->addData($data);
                $this->session->setPageData($model->getData());
                $model->save();

                if ($finderId) {
                    foreach ($model->getDropdowns() as $dropdown) {
                        $prefix = 'dropdown_'.$dropdown->getId();
                        $dropdown->setName($model->getData($prefix.'_name'));
                        $dropdown->setSort($model->getData($prefix.'_sort'));
                        $dropdown->setRange($model->getData($prefix.'_range'));
                        $dropdown->save();
                    }
                } else {
                    for ($i = 0; $i < $model->getCnt(); ++$i) {
                        /** @var $dropdown \Amasty\Finder\Model\Dropdown */
                        $dropdown = $this->dropdownRepository->getDropdownModel();
                        $dropdown->setPos($i);
                        $dropdown->setFinderId($model->getId());
                        $this->dropdownRepository->save($dropdown);
                    }
                }

                $resUniversal = $model->importUniversal($this->getRequest()->getFiles('importuniversal_file'));
                foreach ($resUniversal as $errMsg) {
                    $this->messageManager->addError($errMsg);
                }

                $this->messageManager->addSuccess(__('You saved the item.'));
                $this->session->setPageData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('amasty_finder/*/edit', ['id' => $model->getId()]);
                    return;
                }
                $this->_redirect('amasty_finder/*/');
                return;
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
                $finderId = (int)$this->getRequest()->getParam('id');
                if (!empty($finderId)) {
                    $this->_redirect('amasty_finder/*/edit', ['id' => $finderId]);
                } else {
                    $this->_redirect('amasty_finder/*/new');
                }
                return;
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
                $this->messageManager->addError(
                    __('Something went wrong while saving the item data. Please review the error log.')
                );
                $this->logInterface->critical($e);
                $this->session->setPageData($data);
                $this->_redirect('amasty_finder/*/edit', ['id' => $this->getRequest()->getParam('id')]);
                return;
            }
        }
        $this->_redirect('amasty_finder/*/');
    }
}
