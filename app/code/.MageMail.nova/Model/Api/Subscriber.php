<?php
namespace KJ\MageMail\Model\Api;


class Subscriber extends \KJ\MageMail\Model\Api\ApiAbstract
{
    protected $_primaryKeyField = 'subscriber_id';
    protected $_updatedAtField = 'magemail_updated_at';

    const STATUS_SUBSCRIBED     = 1;
    const STATUS_NOT_ACTIVE     = 2;
    const STATUS_UNSUBSCRIBED   = 3;
    const STATUS_UNCONFIRMED    = 4;

    /**
     * @var \Magento\Newsletter\Model\ResourceModel\Subscriber\CollectionFactory
     */
    protected $newsletterResourceModelSubscriberCollectionFactory;

    public function __construct(
        \Magento\Framework\App\ResourceConnection $resourceConnection,
        \KJ\MageMail\Model\DataCollectionFactory $collectionFactory,
        \Magento\Newsletter\Model\ResourceModel\Subscriber\CollectionFactory $newsletterResourceModelSubscriberCollectionFactory
    ) {
        $this->newsletterResourceModelSubscriberCollectionFactory = $newsletterResourceModelSubscriberCollectionFactory;
        parent::__construct($resourceConnection, $collectionFactory);

    }
    protected function _buildCollection($params)
    {
        $size = array_key_exists('size', $params)?$params['size']:10;

        $subscribers = $this->newsletterResourceModelSubscriberCollectionFactory->create()->addFieldToFilter('subscriber_email', array('neq' => ''))
            ->setPageSize($size);

        $this->_filterIncremental($params, $subscribers);

        return $subscribers;
    }

    protected function _getArrayKeys($params)
    {
        return array(
            'subscriber_email'         => 'email',
            'subscriber_id'            => 'external_subscriber_id',
            'subscriber_status'        => 'subscriber_status',
            'store_id'                 => 'external_store_id',
            'magemail_updated_at'      => 'external_subscriber_updated_at'
        );
    }
}