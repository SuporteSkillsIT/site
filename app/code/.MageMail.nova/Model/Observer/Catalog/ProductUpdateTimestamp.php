<?php

namespace KJ\MageMail\Model\Observer\Catalog;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

/**
 * Magento Bug: Products updated_at field is not updating on save #6683
 * https://github.com/magento/magento2/issues/6683
 * Magento 2.1.4 and previous versions don't update the field updated_at in the table catalog_product_entity
 * when a product is updated. This simple fix uses raw SQL to force update the field.
 *
 * Class ProductUpdateTimestamp
 * @package KJ\MageMail\Model\Observer\Catalog
 */

class ProductUpdateTimestamp implements ObserverInterface
{
    private $resource;

    public function __construct(\Magento\Framework\App\ResourceConnection $resource)
    {
        $this->resource = $resource;
    }

    /**
     * Execute observer
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        $product = $observer->getProduct();

        if ($product && $product->getId()) {
            $productId = $product->getId();

            $connection = $this->resource->getConnection();
            $tableName = $this->resource->getTableName('catalog_product_entity');
            $connection->query(
                "UPDATE `" . $tableName . "` SET `updated_at` = NOW() WHERE `entity_id` =  '" . $productId . "' LIMIT 1"
            );
        }
    }
}