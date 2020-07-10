<?php
namespace KJ\MageMail\Model\Api;


class WishlistItem extends \KJ\MageMail\Model\Api\ApiAbstract
{
    protected $_primaryKeyField ='wishlist_item_id';
    protected $_updatedAtField = 'wishlist.updated_at';

    /**
     * @var \Magento\Wishlist\Model\ResourceModel\Item\CollectionFactory
     */
    protected $wishlistResourceModelItemCollectionFactory;

    public function __construct(
        \Magento\Framework\App\ResourceConnection $resourceConnection,
        \KJ\MageMail\Model\DataCollectionFactory $collectionFactory,
        \Magento\Wishlist\Model\ResourceModel\Item\CollectionFactory $wishlistResourceModelItemCollectionFactory
    ) {
        $this->wishlistResourceModelItemCollectionFactory = $wishlistResourceModelItemCollectionFactory;
        parent::__construct($resourceConnection, $collectionFactory);

    }
    protected function _getArrayKeys($params)
    {
        return array(
            'store_id'                  => 'external_store_id',
            'description'               => 'description',
            'qty'                       => 'qty',
            'wishlist_id'               => 'external_wishlist_id',
            'wishlist_item_id'          => 'external_item_id',
            'product_id'                => 'external_product_id',
            'updated_at'                => 'external_updated_at',
        );
    }

    protected function _buildCollection($params)
    {
        $size = array_key_exists('size', $params)?$params['size']:10;

        $collection = $this->wishlistResourceModelItemCollectionFactory->create();
        $collection->setPageSize($size);

        $collection->getSelect()->joinInner(
            array('wishlist' => $this->_table('wishlist')),
            "wishlist.wishlist_id = main_table.wishlist_id",
            array('updated_at')
        );

        $this->_filterIncremental($params, $collection);

        return $collection;
    }
}