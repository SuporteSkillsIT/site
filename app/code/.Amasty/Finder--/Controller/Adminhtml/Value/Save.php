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

class Save extends \Amasty\Finder\Controller\Adminhtml\Value
{
    /**
     * Dispatch request
     * @throws \Magento\Framework\Exception\NotFoundException
     */
    public function execute()
    {
        $this->_initModel();
        $chainId = $this->getRequest()->getParam('id');
        $data = $this->getRequest()->getPostValue();

        if ($data && ($chainId || isset($data['new_finder']))) {
            try {
                $inputFilter = new \Zend_Filter_Input([], [], $data);
                $data = $inputFilter->getUnescaped();
                $this->session->setPageData($data);

                if ($chainId) {
                    $newData = [];
                    foreach ($data as $element => $arrayValue) {
                        if (substr($element, 0, 6) == 'label_') {
                            $valueId = (int)(substr($element, 6));
                            $value = $this->valueRepository->getById($valueId);
                            $dropdownId = $value->getDropdownId();
                            unset($data[$element]);
                            $newData['label_' . $dropdownId] = $arrayValue;
                        }
                    }
                    $data = array_merge($data, $newData);

                    $model = $this->finderRepository->getFinderModel();
                    $newId = $model->newSetterId($chainId);
                    $model->deleteMapRow($chainId);

                    $currentId = $newId;

                    while (($currentId) && ($model->isDeletable($currentId))) {
                        $value = $this->valueRepository->getById($currentId);
                        $currentId = $value->getParentId();
                        $value->delete();
                    }

                    $this->messageManager->addSuccess(__('Product has been deleted'));
                }
                $finderId = $this->valueRepository->saveNewFinder($data);
                $this->messageManager->addSuccess(__('Record have been successfully saved'));
                $this->session->setPageData(false);

                $this->_redirect('*/finder/edit', ['id' => $finderId]);
                return;
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
                $id = (int)$this->getRequest()->getParam('id');
                if (!empty($id)) {
                    $this->_redirect('amasty_finder/value/edit', ['id' => $id, 'finder_id' => $this->model->getId()]);
                } else {
                    $this->_redirect('amasty_finder/value/new', ['finder_id' => $this->model->getId()]);
                }
                return;
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
                $this->messageManager->addError(
                    __('Something went wrong while saving the item data. Please review the error log.')
                );
                $this->logger->critical($e);
                $this->session->setPageData($data);
                $this->_redirect('*/*/edit', [
                    'id' => $this->getRequest()->getParam('id'),
                    'finder_id' => $this->model->getId()
                ]);
                return;
            }
        }
        $this->_redirect('amasty_finder/finder/edit', ['id' => $this->model->getId()]);
    }
}
