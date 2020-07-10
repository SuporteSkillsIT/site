<?php
namespace KJ\MageMail\Test\Model\Api;

use Magento\Catalog\Model\ResourceModel\Category\CollectionFactory;
use Magento\Catalog\Model\ResourceModel\Category\Collection;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;

class CategoryTest extends AbstractTest
{
    /**
     * @var \KJ\MageMail\Test\Model\Api\Product
     */
    protected $model;

    protected function setUp()
    {
        parent::setUp();
        $product = $this->objectManager->getObject(\Magento\Catalog\Model\Category::class);
        $product->setEntityId(15);
        $product->setName('Test product');

        $data = [$product->toArray()];

        $this->collectionFactoryMock = $this->getMockBuilder(CollectionFactory::class)
            ->setMethods(['create'])
            ->disableOriginalConstructor()
            ->getMock();
        $this->collectionMock = $this->getCollectionMock(Collection::class, $data);

        $this->collectionFactoryMock->expects($this->any())
            ->method('create')
            ->willReturn($this->collectionMock);

        $this->model = $this->objectManager->getObject(
            \KJ\MageMail\Model\Api\Category::class,
            ['catalogResourceModelCategoryCollectionFactory' => $this->collectionFactoryMock]
        );
    }

    public function testQuery()
    {
        $query = array('entity_type' => 'categories', 'size' => 500, 'initial_import_completed' => 0, 'last_external_updated_at' => null, 'last_external_entity_id' => 1);

        $results = $this->model->query($query);

        $this->assertArrayHasKey('items', $results);

        $this->assertGreaterThanOrEqual(1, count($results['items']));

        $result = $results['items'][0];

        $this->assertGreaterThan(0, $result['external_category_id']);

        $this->assertNotEmpty($result['name']);
    }
}