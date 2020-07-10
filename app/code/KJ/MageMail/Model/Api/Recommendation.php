<?php
namespace KJ\MageMail\Model\Api;

/**
 * This class is used for cross_sells, up_sells and related products. One type at a time.
 * There is no timestamp on this table so the catalog_product_entity table timestamp is used instead.
 * All the recommendations are grouped by external_product_id at PHP level. This is done to detect deletions or de-associations.
 */
class Recommendation extends \KJ\MageMail\Model\Api\ApiAbstract
{
    protected function _buildCollection($params)
    {
        $size = array_key_exists('size', $params)?$params['size']:10;

        $collection = $this->_createCollection();
        $collection->setPageSize($size);

        $linkTypeId = $this->_getLinkTypeId($params['entity_type']);
        $collection->getSelect()
            ->from(array('main_table' => $this->_table('catalog_product_entity')),
                array(
                    'external_product_id'       => 'main_table.entity_id',
                    'external_linked_product_id'=> 'link.linked_product_id',
                    'external_updated_at'       => 'main_table.updated_at'
                ));

        if ($params['initial_import_completed']) {
            $collection->getSelect()->joinLeft(
                array('link' => $this->_table('catalog_product_link')),
                "link.product_id = main_table.entity_id and link.link_type_id = {$linkTypeId}",
                array()
            );
        } else {
            $collection->getSelect()->joinInner(
                array('link' => $this->_table('catalog_product_link')),
                "link.product_id = main_table.entity_id and link.link_type_id = {$linkTypeId}",
                array()
            );
        }

        $this->_filterIncremental($params, $collection);

        return $collection;
    }

    /**
     * Recommended Products (i.e. linked products) are grouped by external_product_id.
     * @param $params
     * @return array
     */
    public function query($params)
    {
        $results = parent::query($params);
        $recommendations = array();

        if (array_key_exists('items', $results)) {
            foreach($results['items'] as $_item) {
                $externalProductId = $_item['external_product_id'];
                $externalLinkedProductId = $_item['external_linked_product_id'];

                if (!array_key_exists($externalProductId, $recommendations)) {
                    $recommendations[$externalProductId] = [
                        'external_product_id' => $externalProductId,
                        'external_updated_at' => $_item['external_updated_at'],
                        'external_linked_product_ids' => []
                    ];
                }

                if (!is_null($externalLinkedProductId)) {
                    $recommendations[$externalProductId]['external_linked_product_ids'][] = $externalLinkedProductId;
                }
            }

            $results['items'] = array_values($recommendations);
        }

        return $results;
    }


    private function _getLinkTypeId($recommendationType)
    {
        $linkTypes = array(
            'relation_recommendations' => 1,
            'up_sell_recommendations' => 4,
            'cross_sell_recommendations' => 5
        );

        return $linkTypes[$recommendationType];
    }
}