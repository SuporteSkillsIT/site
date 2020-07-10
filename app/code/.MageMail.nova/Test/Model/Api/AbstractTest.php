<?php
namespace KJ\MageMail\Test\Model\Api;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;

abstract class AbstractTest  extends \PHPUnit_Framework_TestCase
{
    protected $resourceConnection;
    protected $connection;

    protected function setUp()
    {
        parent::setUp();
        $this->objectManager = new ObjectManager($this);
        $this->resourceConnection = $this->getMock('\Magento\Framework\App\ResourceConnection', [], [], '', false, false);
        $this->connection = $this->getMock('Magento\Framework\DB\Adapter\Pdo\Mysql', [], [], '', false, false);

        $this->resourceConnection->expects(
            $this->any()
        )->method(
            'getConnection'
        )->will(
            $this->returnValue($this->connection)
        );

        $this->connection->expects(
            $this->any()
        )->method(
            'isTableExists'
        )->will(
            $this->returnValue(true)
        );
    }

    public function getCollectionMock($className, array $data)
    {
        if (!is_subclass_of($className, '\Magento\Framework\Data\Collection')) {
            throw new \InvalidArgumentException(
                $className . ' does not instance of \Magento\Framework\Data\Collection'
            );
        }
        $mock = $this->getMock($className, [], [], '', false, false);

        $selectMock = $this->getMock(\Zend_Db_Select::class, [], [], '', false, false);

        $selectMock->expects(
            $this->any()
        )->method(
            'joinLeft'
        )->will(
            $this->returnValue($selectMock)
        );

        $selectMock->expects(
            $this->any()
        )->method(
            'join'
        )->will(
            $this->returnValue($selectMock)
        );

        $selectMock->expects(
            $this->any()
        )->method(
            'where'
        )->will(
            $this->returnValue($selectMock)
        );

        $mock->expects(
            $this->any()
        )->method(
            'addAttributeToSelect'
        )->will(
            $this->returnValue($mock)
        );

        $mock->expects(
            $this->any()
        )->method(
            'setPageSize'
        )->will(
            $this->returnValue($mock)
        );

        $mock->expects(
            $this->any()
        )->method(
            'getSelect'
        )->will(
            $this->returnValue($selectMock)
        );

        $selectMock->expects(
            $this->any()
        )->method(
            'from'
        )->will(
            $this->returnValue($selectMock)
        );

        $mock->expects(
            $this->any()
        )->method(
            'addFieldToFilter'
        )->will(
            $this->returnValue($mock)
        );

        $mock->expects(
            $this->any()
        )->method(
            'toArray'
        )->will(
            $this->returnValue(['items' => $data])
        );
        return $mock;
    }
}