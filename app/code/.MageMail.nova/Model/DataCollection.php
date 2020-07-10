<?php
namespace KJ\MageMail\Model;

use \Zend_Db_Select;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * Class used for building object collections not linked to Magento entities.
 * @package KJ\MageMail\Model
 */
class DataCollection extends AbstractCollection
{
    public function __construct(\Magento\Framework\Data\Collection\EntityFactoryInterface $entityFactory,
        \Psr\Log\LoggerInterface $logger, \Magento\Framework\Data\Collection\Db\FetchStrategyInterface $fetchStrategy,
        \Magento\Framework\Event\ManagerInterface $eventManager,
        DbResource $resource)
    {
        parent::__construct($entityFactory, $logger, $fetchStrategy, $eventManager, null, $resource);
        $this->getSelect()->reset(Zend_Db_Select::FROM);
        $this->getSelect()->reset(Zend_Db_Select::COLUMNS);
    }
}