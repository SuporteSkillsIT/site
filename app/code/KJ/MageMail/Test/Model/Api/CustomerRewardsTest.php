<?php
namespace KJ\MageMail\Test\Model\Api;

use KJ\MageMail\Model\DataCollectionFactory as CollectionFactory;
use KJ\MageMail\Model\DataCollection as Collection;

class CustomerRewardsTest extends AbstractTest
{
    /**
     * @var \KJ\MageMail\Test\Model\Api\Subscriber
     */
    protected $model;

    protected function setUp()
    {
        parent::setUp();
        $store = $this->objectManager->getObject(\Magento\Framework\DataObject::class);
        $store->setCustomerPointsUsable(15);
        $store->setExternalCustomerId(3);

        $data = [$store->toArray()];

        $this->collectionFactoryMock = $this->getMockBuilder(CollectionFactory::class)
            ->setMethods(['create'])
            ->disableOriginalConstructor()
            ->getMock();
        $this->collectionMock = $this->getCollectionMock(Collection::class, $data);

        $this->collectionFactoryMock->expects($this->any())
            ->method('create')
            ->willReturn($this->collectionMock);

        $this->model = $this->objectManager->getObject(
            \KJ\MageMail\Model\Api\CustomerRewards::class,
            ['collectionFactory' => $this->collectionFactoryMock,
                'resourceConnection' => $this->resourceConnection]
        );
    }

    public function testQuery()
    {
        $query = array('entity_type' => 'customer_rewards', 'size' => 500, 'initial_import_completed' => 0, 'last_external_updated_at' => null, 'last_external_entity_id' => 1);

        $results = $this->model->query($query);

        $this->assertArrayHasKey('items', $results);

        $this->assertGreaterThanOrEqual(1, count($results['items']));

        $store = $results['items'][0];

        $this->assertGreaterThan(0, $store['customer_points_usable']);

        $this->assertGreaterThan(0, $store['external_customer_id']);
    }
}