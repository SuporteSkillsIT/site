<?php

namespace Modulesgarden\Crm\Observer;

use Magento\Framework\Event\ObserverInterface;

class BeforeCustomerSaveObserver implements ObserverInterface
{

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $customer = $observer->getEvent()->getCustomer();
        if (isset($_SESSION['modulesgarden_crm_resource_id'])) {
            $customer->setCustomAttribute('connect_with_crm', $_SESSION['modulesgarden_crm_resource_id']);
            unset($_SESSION['modulesgarden_crm_resource_id']);
        }
    }

}
