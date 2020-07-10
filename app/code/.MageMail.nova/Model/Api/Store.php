<?php
namespace KJ\MageMail\Model\Api;

class Store extends \KJ\MageMail\Model\Api\ApiAbstract
{
    protected $_storeManager;

    public function __construct(\Magento\Framework\App\ResourceConnection $resourceConnection,
        \KJ\MageMail\Model\DataCollectionFactory $collectionFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager)
    {
        parent::__construct($resourceConnection, $collectionFactory);
        $this->_storeManager = $storeManager;
    }


    protected function _buildCollection($params)
    {
        $results = [];
        $stores = $this->_storeManager->getStores();

        foreach($stores as $_store) {
            $_website = $this->_storeManager->getWebsite($_store->getWebsiteId());
            $results[] = array(
                'external_store_id'             => $_store->getId(),
                'external_website_id'  => $_store->getWebsiteId(),
                'name' => $_website->getName() . ' - ' . $_store->getName(),
            );
        }

        return $results;
    }

    protected function _filterIncremental($params, $select)
    {
        // Not used
        return $this;
    }

    protected function _toArray($collection)
    {
        return $collection;
    }
}