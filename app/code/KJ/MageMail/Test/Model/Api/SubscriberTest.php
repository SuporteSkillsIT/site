<?php
namespace KJ\MageMail\Test\Model\Api;

use Magento\Newsletter\Model\ResourceModel\Subscriber\CollectionFactory;
use Magento\Newsletter\Model\ResourceModel\Subscriber\Collection;

class SubscriberTest extends AbstractTest
{
    /**
     * @var \KJ\MageMail\Test\Model\Api\Subscriber
     */
    protected $model;

    protected function setUp()
    {
        parent::setUp();
        $customer = $this->objectManager->getObject(\Magento\Newsletter\Model\Subscriber::class);
        $customer->setSubscriberId(15);
        $customer->setSubscriberEmail('Test customer');

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
            \KJ\MageMail\Model\Api\Subscriber::class,
            ['newsletterResourceModelSubscriberCollectionFactory' => $this->collectionFactoryMock]
        );
    }

    public function testQuery()
    {
        $query = array('entity_type' => 'subscribers', 'size' => 500, 'initial_import_completed' => 0, 'last_external_updated_at' => null, 'last_external_entity_id' => 1);

        $results = $this->model->query($query);

        $this->assertArrayHasKey('items', $results);

        $this->assertGreaterThanOrEqual(1, count($results['items']));

        $result = $results['items'][0];

        $this->assertGreaterThan(0, $result['external_subscriber_id']);

        $this->assertNotEmpty($result['email']);
    }
}