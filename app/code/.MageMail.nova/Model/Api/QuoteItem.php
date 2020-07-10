<?php
namespace KJ\MageMail\Model\Api;

class QuoteItem extends \KJ\MageMail\Model\Api\ApiAbstract
{
    protected $_primaryKeyField = 'main_table.item_id';

    protected function _buildCollection($params)
    {
        $size = array_key_exists('size', $params)?$params['size']:10;

        //We cannot use sales/quote_item because of a Magento bug that requires every quote item
        // in a collection to be associated with a quote object. This bug was also in version 1.
        $quoteItems = $this->_createCollection();
        $quoteItems->setPageSize($size);

        $quoteItems->getSelect()
            ->from(array('main_table' => $this->_table('quote_item')))
            ->joinLeft(
                array('options' => $this->_table('quote_item_option')),
                "options.item_id = main_table.item_id and code='product_type' and value = 'grouped'",
                array('group_id' => 'options.product_id')
            );

        if (array_key_exists('include_ticket_information', $params) && $params['include_ticket_information'] == 'yes') {
            $this->includeTicketInformation($quoteItems);
        }

        $quoteItems->addFieldToFilter('parent_item_id', array('null' => true));

        $this->_filterIncremental($params, $quoteItems);

        return $quoteItems;
    }

    protected function _getArrayKeys($params)
    {
        return array(
            'qty' => 'qty',
            'sku' => 'sku',
            'store_id' =>'external_store_id',
            'quote_id' => 'external_quote_id',
            'item_id' => 'external_item_id',
            'group_id' => 'group_id',
            'product_id' => 'external_product_id',
            'created_at' => 'external_created_at',
            'updated_at' => 'external_updated_at',
            'original_product_id' => 'associated_product_id',
        );
    }

    private function includeTicketInformation($quoteItems)
    {
        $quoteItems->getSelect()
            ->joinLeft(
                array('ticket' => $this->_table('ticketing_seat_queue')),
                "ticket.id = main_table.seat_queue_id",
                array('original_product_id' => 'ticket.event_id')
            );
    }
}