<?php
namespace KJ\MageMail\Model\Api;


class Customer extends \KJ\MageMail\Model\Api\ApiAbstract
{

    /**
     * @var \Magento\Customer\Model\ResourceModel\Customer\CollectionFactory
     */
    protected $customerResourceModelCustomerCollectionFactory;

    public function __construct(
        \Magento\Framework\App\ResourceConnection $resourceConnection,
        \KJ\MageMail\Model\DataCollectionFactory $collectionFactory,
        \Magento\Customer\Model\ResourceModel\Customer\CollectionFactory $customerResourceModelCustomerCollectionFactory
    ) {
        $this->customerResourceModelCustomerCollectionFactory = $customerResourceModelCustomerCollectionFactory;
        parent::__construct($resourceConnection, $collectionFactory);
    }
    protected function _buildCollection($params)
    {
        $size = array_key_exists('size', $params)?$params['size']:10;

        $customers = $this->customerResourceModelCustomerCollectionFactory->create()->addAttributeToSelect('gender')
            ->addAttributeToSelect('firstname')
            ->addAttributeToSelect('dob')
            ->addAttributeToSelect('lastname')
            ->addAttributeToSelect('prefix')
            ->setPageSize($size);

        $this->_filterIncremental($params, $customers);

        return $customers;
    }

    protected function _getValue($key, $value)
    {
        if ($key == 'gender') {
            switch($value) {
                case '1':
                    return 'male';
                case '2':
                    return 'female';
            }
        }
        return $value;
    }

    protected function _getArrayKeys($params)
    {
        return array(
            'email'                => 'email',
            'gender'               => "gender",
            'prefix'               => 'prefix',
            'firstname'            => 'first_name',
            'lastname'             => 'last_name',
            'dob'                  => 'dob',
            'entity_id'            => 'external_customer_id',
            'group_id'             => 'external_customer_group_id',
            'updated_at'           => 'external_customer_updated_at',
            'store_id'             => 'external_store_id',
        );
    }
}