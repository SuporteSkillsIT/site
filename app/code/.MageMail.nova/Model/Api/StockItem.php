<?php
namespace KJ\MageMail\Model\Api;

use Magento\CatalogInventory\Api\StockRepositoryInterface;

class StockItem extends \KJ\MageMail\Model\Api\ApiAbstract
{
    protected $_primaryKeyField = 'item_id';
    protected $_updatedAtField = 'stock_item.updated_at';

    /**
     * @var \Magento\CatalogInventory\Model\ResourceModel\Stock\Item\CollectionFactory
     */
    protected $stockRepository;

    protected $helperData;

    public function __construct(
        \Magento\Framework\App\ResourceConnection $resourceConnection,
        \KJ\MageMail\Model\DataCollectionFactory $collectionFactory
    ) {
        parent::__construct($resourceConnection, $collectionFactory);
    }

    protected function _buildCollection($params)
    {
        $size = array_key_exists('size', $params)?$params['size']:10;
        $stockItems = $this->_createCollection();
        $stockItems->setPageSize($size);

        $stockItems->getSelect()
            ->from(array('main_table' => $this->_table('cataloginventory_stock_status')), array(
                'external_item_id'        => 'stock_item.item_id',
                'external_product_id'     => 'main_table.product_id',
                'external_updated_at'     => 'stock_item.updated_at',
                'stock_type'              => 'stock.stock_name',
                'qty'                     => 'main_table.qty',
                'is_in_stock'             => 'main_table.stock_status',
                'use_config_manage_stock' => 'stock_item.use_config_manage_stock',
            ));

        $stockItems->addFieldToFilter('main_table.qty', array('notnull' => true));

        $stockItems->getSelect()->join(
            array('stock' => $this->_table('cataloginventory_stock')),
            "stock.stock_id = main_table.stock_id",
            array('stock_name')
        );

        $stockItems->getSelect()->join(
            array('stock_item' => $this->_table('cataloginventory_stock_item')),
            "stock_item.product_id = main_table.product_id",
            array('item_id', 'updated_at', 'use_config_manage_stock')
        );

        $this->_filterIncremental($params, $stockItems);

        return $stockItems;
    }

    protected function _filterIncremental($params, $collection)
    {
        if (array_key_exists('initial_import_completed', $params) && $params['initial_import_completed']) {
            if ($params['last_external_updated_at']) {
                $collection->addFieldToFilter($this->_updatedAtField, array('gt' => $params['last_external_updated_at']));
                $collection->setOrder($this->_updatedAtField, 'ASC');
            }
        } else {
            if ($params['last_external_entity_id']) {
                $collection->addFieldToFilter($this->_primaryKeyField, array('gt' => $params['last_external_entity_id']));
                $collection->setOrder($this->_primaryKeyField, 'ASC');
            }
            if ($this->getBatchValue()) {
                $tableAlias = 'stock_item';
                $collection->getSelect()->where($tableAlias . '.' . $this->_primaryKeyField . ' % 3 = ?', $this->getBatchValue() -1);
            }
        }
    }
}