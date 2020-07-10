<?php
namespace KJ\MageMail\Model\Api;


class Quote extends \KJ\MageMail\Model\Api\ApiAbstract
{

    /**
     * @var \Magento\Quote\Model\ResourceModel\Quote\CollectionFactory
     */
    protected $quoteResourceModelQuoteCollectionFactory;

    public function __construct(
        \Magento\Framework\App\ResourceConnection $resourceConnection,
        \KJ\MageMail\Model\DataCollectionFactory $collectionFactory,
        \Magento\Quote\Model\ResourceModel\Quote\CollectionFactory $quoteResourceModelQuoteCollectionFactory
    ) {
        $this->quoteResourceModelQuoteCollectionFactory = $quoteResourceModelQuoteCollectionFactory;
        parent::__construct($resourceConnection, $collectionFactory);
    }
    protected function _getArrayKeys($params)
    {
        return array(
            'customer_email'                         => 'email',
            'is_active'                  => 'is_converted',
            'grand_total'                   => 'grand_total',
            'quote_currency_code'                      => 'currency',
            'entity_id'             => 'external_quote_id',
            'customer_id'          => 'external_customer_id',
            'customer_group_id'             => 'customer_group_id',
            'created_at'           => 'external_created_at',
            'updated_at'           => 'external_updated_at',
            'store_id'             => 'external_store_id',
            'remote_ip'                 => 'remote_ip',
            'now'                   => 'now',
        );
    }

    protected function _buildCollection($params)
    {
        $size = array_key_exists('size', $params)?$params['size']:10;

        $quote = $this->quoteResourceModelQuoteCollectionFactory->create();
        $quote->addFieldToFilter('customer_email', array('notnull' => true));
        $quote->setPageSize($size);

        if (array_key_exists('email', $params)) {
            $email = $params['email'];
            $quote->getSelect()->columns(array('now' => new \Zend_Db_Expr('NOW()')));
            $quote->addFieldToFilter('customer_email', $email)
                ->setOrder('entity_id', 'desc');
        } else {
            $quote->addFieldToFilter('grand_total', ['gt' => 0]);
            $this->_filterIncremental($params, $quote);
        }

        return $quote;
    }
}