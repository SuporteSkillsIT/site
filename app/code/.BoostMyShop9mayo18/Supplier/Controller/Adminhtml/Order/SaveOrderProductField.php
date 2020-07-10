<?php

namespace BoostMyShop\Supplier\Controller\Adminhtml\Order;

use Magento\Framework\Controller\ResultFactory;

class SaveOrderProductField extends \BoostMyShop\Supplier\Controller\Adminhtml\Order
{
    /**
     * @return void
     */
    public function execute()
    {
        $result = ['success' => true, 'message' => ''];

        try
        {
            $popId = (int)$this->getRequest()->getPost('pop_id');
            $field = $this->getRequest()->getPost('field');
            $value = $this->getRequest()->getPost('value');

            $pop = $this->_orderProductFactory->create()->load($popId);
            $pop->setData($field, $value)->save();

            $result['success'] = true;
        }
        catch(\Exception $ex)
        {
            $result['success'] = false;
            $result['message'] = $ex->getMessage();
        }

        $resultJson = $this->resultFactory->create(ResultFactory::TYPE_JSON);
        $resultJson->setData($result);
        return $resultJson;
    }
}
