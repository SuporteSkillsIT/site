<?php
namespace KJ\MageMail\Model\Api;


class AheadworksSubscription extends \KJ\MageMail\Model\Api\ApiAbstract
{
    protected function _buildCollection($params)
    {
        $collection = $this->_createCollection();

        $collection->getSelect()->from(
            array('flat_subscription' => $this->_table('aw_sarp_flat_subscriptions')), array(
            'email'                     => 'flat_subscription.customer_email',
            'payment_date'              => 'flat_subscription.flat_next_payment_date',
            'external_subscription_id'  => 'flat_subscription.subscription_id',
            'firstname'                 => 'attribute_firstname.value',
            'lastname'                  => 'attribute_lastname.value',
            'item_external_product_ids' => "CONCAT(',', GROUP_CONCAT(DISTINCT item.product_id), ',')",
            'subscription_name'         => 'item.name',
            'subscription_amount'       => 'attribute_price.value',
            'external_customer_id'      => 'subscription.customer_id',
            'external_updated_at'       => 'flat_subscription.magemail_updated_at', //-> Create field directly. Cannot be done via connector
        ))
            ->joinLeft(
                array('subscription' => $this->_table('aw_sarp_subscriptions')),
                "subscription.id = flat_subscription.subscription_id",
                array()
            )
            ->joinLeft(
                array('subscription_item' => $this->_table('aw_sarp_subscription_items')),
                "flat_subscription.subscription_id = subscription_item.subscription_id",
                array()
            )
            ->joinLeft(
                array('item' => $this->_table('sales_flat_order_item')),
                "subscription_item.primary_order_item_id = item.item_id",
                array()
            )
            ->joinLeft(
                array('product' => $this->_table('catalog_product_entity')),
                "product.sku = flat_subscription.products_sku",
                array()
            )
            ->where('flat_subscription.customer_email IS NOT NULL')
            ->group('flat_subscription.customer_email');

        $this->_filterIncremental($params, $collection);

        return $collection;
    }

    public function query($params)
    {
        if (!($this->_isTableExists('aw_sarp_flat_subscriptions'))) {
            return array('count' => 0, 'items' => array());
        }

        return parent::query($params);
    }
}