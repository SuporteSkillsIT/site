<?php

namespace Modulesgarden\Crm\Observer;

use Magento\Framework\Event\ObserverInterface;

class AfterCustomerSaveObserver implements ObserverInterface
{

    protected $resource;

    public function __construct(\Magento\Framework\App\ResourceConnection $resource)
    {
        $this->_resource = $resource;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $resourceID = $observer->getEvent()->getCustomer()->getCustomAttribute('connect_with_crm');
        if (!is_null($resourceID)) {
            $connection = $this->_resource->getConnection(\Magento\Framework\App\ResourceConnection::DEFAULT_CONNECTION);
            $connection->rawQuery('UPDATE `crm_resources` SET `client_id`=' . $observer->getEvent()->getCustomer()->getId()
                    . ' WHERE `id`=' . $resourceID->getValue());
        }
    }

}
