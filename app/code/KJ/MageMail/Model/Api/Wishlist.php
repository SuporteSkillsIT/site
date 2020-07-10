<?php
namespace KJ\MageMail\Model\Api;


class Wishlist extends \KJ\MageMail\Model\Api\ApiAbstract
{
    protected $_primaryKeyField ='wishlist_id';
    protected $_updatedAtField = 'main_table.updated_at';

    /**
     * @var \Magento\Wishlist\Model\ResourceModel\Wishlist\CollectionFactory
     */
    protected $wishlistResourceModelWishlistCollectionFactory;

    public function __construct(
        \Magento\Framework\App\ResourceConnection $resourceConnection,
        \KJ\MageMail\Model\DataCollectionFactory $collectionFactory,
        \Magento\Wishlist\Model\ResourceModel\Wishlist\CollectionFactory $wishlistResourceModelWishlistCollectionFactory
    ) {
        $this->wishlistResourceModelWishlistCollectionFactory = $wishlistResourceModelWishlistCollectionFactory;
        parent::__construct($resourceConnection, $collectionFactory);

    }
    protected function _getArrayKeys($params)
    {
        return array(
            'wishlist_id'          => 'external_wishlist_id',
            'email'                => 'email',
            'sharing_code'         => 'sharing_code',
            'customer_id'          => 'external_customer_id',
            'shared'               => 'shared',
            'updated_at'           => 'external_updated_at',
            'store_id'             => 'external_store_id',
        );
    }

    protected function _buildCollection($params)
    {
        $size = array_key_exists('size', $params)?$params['size']:10;

        $collection = $this->wishlistResourceModelWishlistCollectionFactory->create();
        $collection->setPageSize($size);

        $collection->getSelect()->joinInner(
            array('customer' => $this->_table('customer_entity')),
            "customer.entity_id = main_table.customer_id",
            array('email', 'store_id')
        );

        //Select only wishlists with items (i.e. no empty wishlists)
        $collection->getSelect()->where('wishlist_id IN (SELECT wishlist_id from ' . $this->_table('wishlist_item') . ')');

        $this->_filterIncremental($params, $collection);

        return $collection;
    }
}