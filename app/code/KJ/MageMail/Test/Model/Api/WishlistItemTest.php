<?php

namespace KJ\MageMail\Test\Model\Api;

use Magento\Wishlist\Model\ResourceModel\Item\CollectionFactory;
use Magento\Wishlist\Model\ResourceModel\Item\Collection;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;

class WishlistItemTest extends AbstractTest
{
    /**
     * @var \KJ\MageMail\Test\Model\Api\Customer
     */
    protected $model;

    protected function setUp()
    {
        parent::setUp();
        $customer = $this->objectManager->getObject(\Magento\Wishlist\Model\Wishlist::class);
        $customer->setWishlistId(15);
        $customer->setWishlistItemId(12);

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
            \KJ\MageMail\Model\Api\WishlistItem::class,
            ['wishlistResourceModelItemCollectionFactory' => $this->collectionFactoryMock,
            'resourceConnection' => $this->resourceConnection]
        );
    }

    public function testQuery()
    {
        $query = array('entity_type' => 'wishlist_items', 'size' => 500, 'initial_import_completed' => 0, 'last_external_updated_at' => null, 'last_external_entity_id' => 1);

        $results = $this->model->query($query);

        $this->assertArrayHasKey('items', $results);

        $this->assertGreaterThanOrEqual(1, count($results['items']));

        $store = $results['items'][0];

        $this->assertGreaterThan(0, $store['external_wishlist_id']);
        $this->assertGreaterThan(0, $store['external_item_id']);
    }

}