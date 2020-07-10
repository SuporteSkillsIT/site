<?php

namespace Modulesgarden\Crm\Model\ResourceModel;

class Customer extends \Magento\Customer\Model\ResourceModel\Customer
{

    public function __construct(\Magento\Eav\Model\Entity\Context $context,
            \Magento\Framework\Model\ResourceModel\Db\VersionControl\Snapshot $entitySnapshot,
            \Magento\Framework\Model\ResourceModel\Db\VersionControl\RelationComposite $entityRelationComposite,
            \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
            \Magento\Framework\Validator\Factory $validatorFactory,
            \Magento\Framework\Stdlib\DateTime $dateTime,
            \Magento\Store\Model\StoreManagerInterface $storeManager,
            $data = array())
    {
        parent::__construct($context, $entitySnapshot, $entityRelationComposite, $scopeConfig, $validatorFactory, $dateTime, $storeManager, $data);
    }

}
