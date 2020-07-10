<?php
namespace KJ\MageMail\Model\Api;

use KJ\MageMail\Model\DataCollectionFactory;

abstract class ApiAbstract
{
    protected $_primaryKeyField = 'entity_id';
    protected $_updatedAtField = 'updated_at';

    /**
     * @var \Magento\Framework\App\ResourceConnection
     */
    protected $resourceConnection;

    /**
     * @var DataCollection
     */
    protected $collectionFactory;
    protected $_batchValue = 0;

    public function getBatchValue()
    {
        return $this->_batchValue;
    }

    public function setBatchValue($batchValue)
    {
        $this->_batchValue = $batchValue;
        return $this;
    }

    protected function _processItem($item, $params)
    {
        return $this->_renameArrayKeys($item, $this->_getArrayKeys($params));
    }

    public function __construct(
        \Magento\Framework\App\ResourceConnection $resourceConnection,
        DataCollectionFactory $collectionFactory) {
        $this->collectionFactory = $collectionFactory;
        $this->resourceConnection = $resourceConnection;
    }
    public function query($params)
    {
        $collection = $this->_buildCollection($params);

        if (array_key_exists('count', $params) && $params['count']) {
            $resultArray = array('count' => $collection->getSize());
        } else {
            $resultArray['items'] = $this->_toArray($collection);
            if (is_array($this->_getArrayKeys($params))) {
                $items = array();
                foreach ($resultArray['items'] as $_item) {
                    $items[] = $this->_processItem($_item, $params);
                }
                $resultArray['items'] = $items;
            }
        }

        return $resultArray;
    }

    protected function _toArray($collection)
    {
        $collection->load();
        $ar = $collection->toArray();
        if (array_key_exists('items', $ar)) {
            return $ar['items'];
        }

        return $ar;
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
                $primaryKeyField = $this->_primaryKeyField;

                if (strpos($primaryKeyField, '.') === false) {
                    $formArray = $collection->getSelect()->getPart('from');
                    $tableAlias = array_keys($formArray);
                    $primaryKeyField = $tableAlias[0] . '.' . $this->_primaryKeyField ;
                }
                $collection->getSelect()->where($primaryKeyField . ' % 3 = ?', $this->getBatchValue() -1);
            }
        }
    }

    /**
     * Rename array keys and discard $oldKeys with no matches
     *
     * @param $oldKeys
     * @param $newKeys
     * @return array
     */
    protected function _renameArrayKeys($oldKeys, $newKeys)
    {
        $newDataArray = array();
        foreach ($oldKeys as $_oldKey => $_value) {
            if (array_key_exists($_oldKey, $newKeys)) {
                $newDataArray[$newKeys[$_oldKey]] = $this->_getValue($newKeys[$_oldKey], $_value);
            }
        }

        return $newDataArray;
    }

    protected function _getValue($key, $value)
    {
        return $value;
    }

    /**
     * Return null if you don't want to override the array keys.
     * @param $params
     * @return array|null
     */
    protected function _getArrayKeys($params)
    {
        //No array keys need to be renamed
        return null;
    }

    abstract protected function _buildCollection($params);

    protected function _getReadConnection()
    {
        return $this->resourceConnection;
    }

    protected function _createCollection()
    {
        return $this->collectionFactory->create();
    }

    protected function _table($table)
    {
        return $this->_getReadConnection()->getTableName($table);
    }

    protected function _isTableExists($table)
    {
        return $this->resourceConnection->getConnection(ResourceConnection::DEFAULT_CONNECTION)->isTableExists($table);
    }
}