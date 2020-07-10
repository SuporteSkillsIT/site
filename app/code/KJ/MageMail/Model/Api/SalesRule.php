<?php
namespace KJ\MageMail\Model\Api;

class SalesRule extends \KJ\MageMail\Model\Api\ApiAbstract
{
    /**
     * @var \Magento\SalesRule\Model\ResourceModel\Rule\CollectionFactory
     */
    protected $salesRuleResourceModelRuleCollectionFactory;

    public function __construct(
        \Magento\Framework\App\ResourceConnection $resourceConnection,
        \KJ\MageMail\Model\DataCollectionFactory $collectionFactory,
        \Magento\SalesRule\Model\ResourceModel\Rule\CollectionFactory $salesRuleResourceModelRuleCollectionFactory
    ) {
        $this->salesRuleResourceModelRuleCollectionFactory = $salesRuleResourceModelRuleCollectionFactory;
        parent::__construct($resourceConnection, $collectionFactory);

    }
    protected function _buildCollection($params)
    {
        $size = array_key_exists('size', $params)?$params['size']:10;

        $collection = $this->salesRuleResourceModelRuleCollectionFactory->create()->setPageSize($size);

        $collection->addFieldToFilter('coupon_type', 2)
            ->addFieldToFilter('use_auto_generation', 1);


        return $collection;
    }

    protected function _getArrayKeys($params)
    {
        return array(
            'rule_id'    => 'external_sales_rule_id',
            'name'       => 'name',
        );
    }

    protected function _filterIncremental($params, $select)
    {
        //It will return all sales rules
        return $this;
    }
}