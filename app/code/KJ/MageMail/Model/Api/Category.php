<?php
namespace KJ\MageMail\Model\Api;


class Category extends \KJ\MageMail\Model\Api\ApiAbstract
{

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory
     */
    protected $catalogResourceModelCategoryCollectionFactory;

    public function __construct(
        \Magento\Framework\App\ResourceConnection $resourceConnection,
        \KJ\MageMail\Model\DataCollectionFactory $collectionFactory,
        \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory $catalogResourceModelCategoryCollectionFactory
    ) {
        $this->catalogResourceModelCategoryCollectionFactory = $catalogResourceModelCategoryCollectionFactory;
        parent::__construct($resourceConnection, $collectionFactory);
    }
    protected function _getArrayKeys($params)
    {
        return array(
            'name'                 => 'name',
            'entity_id'            => 'external_category_id',
            'parent_id'            => 'external_parent_category_id',
            'created_at'           => 'external_created_at',
            'updated_at'           => 'external_updated_at',
        );
    }

    protected function _buildCollection($params)
    {
        $size = array_key_exists('size', $params)?$params['size']:10;

        $categories = $this->catalogResourceModelCategoryCollectionFactory->create()->addAttributeToSelect('name');
        $categories->setPageSize($size);

        $this->_filterIncremental($params, $categories);

        return $categories;
    }
}