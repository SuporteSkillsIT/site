<?php
namespace KJ\MageMail\Model\Api;

class Product extends \KJ\MageMail\Model\Api\ApiAbstract
{
    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    protected $catalogResourceModelProductCollectionFactory;

    protected $urlGenerator;

    protected $mageMailHelper;

    protected $_eavAttribute;

    public function __construct(
        \Magento\Framework\App\ResourceConnection $resourceConnection,
        \KJ\MageMail\Helper\Data $mageMailHelper,
        \KJ\MageMail\Model\DataCollectionFactory $collectionFactory,
        \Magento\CatalogUrlRewrite\Model\ProductUrlPathGenerator $productUrlPathGenerator,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $catalogResourceModelProductCollectionFactory,
        \Magento\Eav\Model\Entity\Attribute $entityAttribute
    ) {
        $this->catalogResourceModelProductCollectionFactory = $catalogResourceModelProductCollectionFactory;
        $this->urlGenerator = $productUrlPathGenerator;
        $this->mageMailHelper = $mageMailHelper;
        $this->_eavAttribute = $entityAttribute;
        parent::__construct($resourceConnection, $collectionFactory);

    }

    public function getIdByCode($attributeCode)
    {
        return $this->_eavAttribute
            ->loadByCode('catalog_product', $attributeCode)
            ->getAttributeId();
    }

    protected function _buildCollection($params)
    {
        $size = array_key_exists('size', $params)?$params['size']:10;

        $products = $this->catalogResourceModelProductCollectionFactory->create();
        $products->setPageSize($size);

        if (array_key_exists('website_ids', $params) && count($params['website_ids'])) {
            $products->addWebsiteFilter($params['website_ids']);
        }

        $this->_filterIncremental($params, $products);

        $attributes = array(
            'updated_at',
            'price',
            'special_price',
            'special_from_date',
            'special_to_date',
            'name',
            'sku',
            'status',
            'visibility',
            'url_key',
            $this->_getReplenishmentAttributeCode($params)
        );
        $products->addPriceData();

        if ($eventDateAttributeCode = $this->_getEventDateAttributeCode($params)) {
            $eventDateId = $this->getIdByCode($eventDateAttributeCode);
            $eventStartTimeId = $this->getIdByCode($this->_getEventStartAttributeCode($params));
            $eventDoorTimeId = $this->getIdByCode($this->_getEventDoorAttributeCode($params));
            $products->getSelect()->joinLeft(
                array('event_data_product_attribute' => $this->_table('catalog_product_entity_datetime')),
                "event_data_product_attribute.entity_id = e.entity_id and event_data_product_attribute.attribute_id = {$eventDateId}",
                array('event_date' => 'event_data_product_attribute.value')
            )->joinLeft(
                array('event_start_time_product_attribute' => $this->_table('catalog_product_entity_varchar')),
                "event_start_time_product_attribute.entity_id = e.entity_id and event_start_time_product_attribute.attribute_id = {$eventStartTimeId}",
                array('event_start_time' => 'event_start_time_product_attribute.value')
            )->joinLeft(
                array('event_door_time_product_attribute' => $this->_table('catalog_product_entity_varchar')),
                "event_door_time_product_attribute.entity_id = e.entity_id and event_door_time_product_attribute.attribute_id = {$eventDoorTimeId}",
                array('event_door_time' => 'event_door_time_product_attribute.value')
            );
        }

        $products->addAttributeToSelect($attributes);

        if (!array_key_exists('count', $params) || !$params['count']) {
            $products->getSelect()->joinLeft(
                array('category_product' => $this->_table('catalog_category_product')),
                "category_product.product_id = e.entity_id",
                array(
                    'category_ids' => 'GROUP_CONCAT(DISTINCT category_product.category_id)',
                )
            )->group('e.entity_id');
        }

        foreach ($products as $_product) {
            if (is_null($_product->getUrlKey())) { //Only simple products have a URL key.
                $url = $_product->getProductUrl(false);
                $_product->setUrlKey(trim(parse_url($url, PHP_URL_PATH)), '/');
            }
        }

        return $products;
    }

    public function query($params)
    {
        $result = parent::query($params);
        $result['product_url_suffix'] = $this->mageMailHelper->getProductSuffix();

        return $result;
    }

    protected function _getEventDateAttributeCode($params)
    {
        if (!array_key_exists('event_date_attribute_code', $params)) {
            return false;
        }

        return $params['event_date_attribute_code'];
    }

    protected function _getEventStartAttributeCode($params)
    {
        if (!array_key_exists('event_start_attribute_code', $params)) {
            return 'event_start_time';
        }

        return $params['event_start_attribute_code'];
    }

    protected function _getEventDoorAttributeCode($params)
    {
        if (!array_key_exists('event_door_attribute_code', $params)) {
            return 'event_door_time';
        }

        return $params['event_door_attribute_code'];
    }

    protected function _getReplenishmentAttributeCode($params)
    {
        if (!array_key_exists('replenishment_attribute_code', $params)) {
            throw new \Exception('Replenishment attribute code is not defined: ' . var_export($params, true));
        }

        return $params['replenishment_attribute_code'];
    }

    protected function _getArrayKeys($params)
    {
        return [
            'entity_id'             => 'external_product_id',
            'updated_at'            => 'external_updated_at',
            'price'                 => 'price',
            'special_price'         => 'special_price',
            'special_from_date'     => 'special_from_date',
            'special_to_date'       => 'special_to_date',
            'category_ids'          => 'category_ids',
            'sku'                   => 'sku',
            'url_key'               => 'url',
            $this->_getReplenishmentAttributeCode($params) => 'days_to_replenish',
            'name'                  => 'name',
            'event_date' => 'event_date',
            'status'                => 'status',
            'visibility'            => 'visibility',
            'minimal_price'         => 'minimal_price',
            'event_date'  => 'event_date',
            'event_start_time'  => 'event_start_time',
            'event_door_time'  => 'event_door_time',
        ];
    }
}