<?php
namespace KJ\MageMail\Model\Api;


class CustomerGroup extends \KJ\MageMail\Model\Api\ApiAbstract
{

    /**
     * @var \Magento\Customer\Model\ResourceModel\Group\CollectionFactory
     */
    protected $customerResourceModelGroupCollectionFactory;

    public function __construct(
        \Magento\Framework\App\ResourceConnection $resourceConnection,
        \KJ\MageMail\Model\DataCollectionFactory $collectionFactory,
        \Magento\Customer\Model\ResourceModel\Group\CollectionFactory $customerResourceModelGroupCollectionFactory
    ) {
        $this->customerResourceModelGroupCollectionFactory = $customerResourceModelGroupCollectionFactory;
        parent::__construct($resourceConnection, $collectionFactory);
    }
    protected function _buildCollection($params)
    {
        $collection =  $this->customerResourceModelGroupCollectionFactory->create();
        return $collection;
    }

    protected function _getArrayKeys($params)
    {
        return array(
            'customer_group_code'                          => 'name',
            'customer_group_id'    => 'external_customer_group_id',
        );
    }


    protected function _filterIncremental($params, $select)
    {
        //Not needed
        return $this;
    }
}