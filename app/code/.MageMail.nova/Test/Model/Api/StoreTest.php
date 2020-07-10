<?php
namespace KJ\MageMail\Test\Model\Api;

use KJ\MageMail\Model\DataCollectionFactory as CollectionFactory;
use Magento\Store\Model\Store;
use Magento\Store\Model\Website;

//use Magento\Store\Model\StoreManager;

class StoreTest extends AbstractTest
{
    /**
     * @var \KJ\MageMail\Test\Model\Api\Subscriber
     */
    protected $model;

    protected function setUp()
    {
        parent::setUp();
        $this->storeManager = $this->getMock('\Magento\Store\Model\StoreManager', [], [], '', false, false);

        $store = $this->objectManager->getObject(Store::class);
        $store->setName('Test Store');

        $this->storeManager->expects(
            $this->any()
        )->method(
            'getStores'
        )->will(
            $this->returnValue([$store])
        );

        $website = $this->objectManager->getObject(Website::class);
        $website->setName('Test Website');

        $this->storeManager->expects(
            $this->any()
        )->method(
            'getWebsite'
        )->will(
            $this->returnValue($website)
        );

        $this->model = $this->objectManager->getObject(
            \KJ\MageMail\Model\Api\Store::class,
            ['storeManager' => $this->storeManager,
                'resourceConnection' => $this->resourceConnection]
        );
    }

    public function testQuery()
    {
        $query = array('entity_type' => 'stores', 'size' => 500, 'initial_import_completed' => 0, 'last_external_updated_at' => null, 'last_external_entity_id' => 1);

        $results = $this->model->query($query);

        $this->assertArrayHasKey('items', $results);

        $this->assertGreaterThanOrEqual(1, count($results['items']));

        $store = $results['items'][0];

        $this->assertEquals($store['name'], 'Test Website - Test Store');
    }
}