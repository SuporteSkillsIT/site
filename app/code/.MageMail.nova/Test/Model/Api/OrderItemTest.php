<?php

namespace KJ\MageMail\Test\Model\Api;

use Magento\Sales\Model\ResourceModel\Order\Item\CollectionFactory;
use Magento\Sales\Model\ResourceModel\Order\Item\Collection;

class OrderItemTest extends AbstractTest
{
    /**
     * @var \KJ\MageMail\Test\Model\Api\Customer
     */
    protected $model;

    protected function setUp()
    {
        parent::setUp();
        $customer = $this->objectManager->getObject(\Magento\Sales\Model\Order\Item::class);
        $customer->setItemId(15);
        $customer->setSku('product sku');

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
            \KJ\MageMail\Model\Api\OrderItem::class,
            ['salesResourceModelOrderItemCollectionFactory' => $this->collectionFactoryMock,
            'resourceConnection' => $this->resourceConnection]
        );
    }

    public function testQuery()
    {
        $query = array('entity_type' => 'order_items', 'size' => 500, 'initial_import_completed' => 0, 'last_external_updated_at' => null, 'last_external_entity_id' => 1);

        $results = $this->model->query($query);

        $this->assertArrayHasKey('items', $results);

        $this->assertGreaterThanOrEqual(1, count($results['items']));

        $store = $results['items'][0];

        $this->assertGreaterThan(0, $store['external_item_id']);

        $this->assertNotEmpty($store['sku']);
    }

}