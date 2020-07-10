<?php

namespace KJ\MageMail\Test\Model\Api;

use KJ\MageMail\Model\DataCollectionFactory;
use KJ\MageMail\Model\DataCollection;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;

class RecommendationTest extends AbstractTest
{
    /**
     * @var \KJ\MageMail\Test\Model\Api\Customer
     */
    protected $model;

    protected function setUp()
    {
        parent::setUp();
        $productRecommendation = $this->objectManager->getObject(\Magento\Framework\DataObject::class);
        $productRecommendation->setExternalLinkedProductId(15);
        $productRecommendation->setExternalProductId(16);
        $productRecommendation->setExternalUpdatedAt(time());

        $data = [$productRecommendation->toArray()];

        $this->collectionFactoryMock = $this->getMockBuilder(DataCollectionFactory::class)
            ->setMethods(['create'])
            ->disableOriginalConstructor()
            ->getMock();
        $this->collectionMock = $this->getCollectionMock(DataCollection::class, $data);

        $this->collectionFactoryMock->expects($this->any())
            ->method('create')
            ->willReturn($this->collectionMock);

        $this->model = $this->objectManager->getObject(
            \KJ\MageMail\Model\Api\Recommendation::class,
            ['collectionFactory' => $this->collectionFactoryMock,
                'resourceConnection' => $this->resourceConnection]
        );
    }

    public function testQuery()
    {
        $query = array('entity_type' => 'cross_sell_recommendations', 'link_type' => 'relation',
            'size' => 500, 'initial_import_completed' => 1, 'last_external_updated_at' => '2017-01-20 05:45:08',
            'last_external_entity_id' => null);

        $results = $this->model->query($query);

        $this->assertArrayHasKey('items', $results);

        $this->assertGreaterThanOrEqual(1, count($results['items']));

        $store = $results['items'][0];
        $this->assertGreaterThan(0, $store['external_product_id']);

        $this->assertNotEmpty($store['external_updated_at']);
    }
}