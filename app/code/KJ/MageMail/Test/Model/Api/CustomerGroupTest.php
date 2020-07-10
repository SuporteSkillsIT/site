<?php
namespace KJ\MageMail\Test\Model\Api;

use Magento\Customer\Model\ResourceModel\Group\CollectionFactory;
use Magento\Customer\Model\ResourceModel\Group\Collection;

class CustomerGroupTest extends AbstractTest
{
    /**
     * @var \KJ\MageMail\Test\Model\Api\Customer
     */
    protected $model;

    protected function setUp()
    {
        parent::setUp();
        $customer = $this->objectManager->getObject(\Magento\Customer\Model\Customer::class);
        $customer->setCustomerGroupId(15);
        $customer->setCustomerGroupCode('Test');

        $data = [$customer->toArray()];

        $this->collectionFactoryMock = $this->getMockBuilder(CollectionFactory::class)
            ->setMethods(['create'])
            ->disableOriginalConstructor()
            ->getMock();
        $this->collectionMock = $this->getCollectionMock(Collection::class, $data);

        $this->collectionFactoryMock->expects($this->any())
            ->method('create')
            ->willReturn($this->collectionMock);

        $this->model = $this->objectManager->getObject(
            \KJ\MageMail\Model\Api\CustomerGroup::class,
            ['customerResourceModelGroupCollectionFactory' => $this->collectionFactoryMock]
        );
    }

    public function testQuery()
    {
        $query = array('entity_type' => 'customer_groups', 'size' => 500, 'initial_import_completed' => 0, 'last_external_updated_at' => null, 'last_external_entity_id' => 1);

        $results = $this->model->query($query);

        $this->assertArrayHasKey('items', $results);

        $this->assertGreaterThanOrEqual(1, count($results['items']));

        $result = $results['items'][0];

        $this->assertGreaterThan(0, $result['external_customer_group_id']);

        $this->assertNotEmpty($result['name']);
    }
}