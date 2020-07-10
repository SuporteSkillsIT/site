<?php
namespace KJ\MageMail\Model;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Class required by DataCollection class in order to build object collections not linked to Magento entities.
 * @package KJ\MageMail\Model
 */
class DbResource extends AbstractDb
{
    /**
     * Resource initialization
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_mainTable = 'test';
    }
}