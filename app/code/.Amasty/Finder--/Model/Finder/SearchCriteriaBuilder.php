<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Finder
 */


namespace Amasty\Finder\Model\Finder;

use Magento\Catalog\Model\ResourceModel\Product\Collection;

/**
 * Class SearchCriteriaBuilder
 * @package Amasty\Finder\Model\Finder
 */
class SearchCriteriaBuilder
{
    /**
     * @var array
     */
    private $aggregation = [];

    /**
     * @param Collection $collection
     * @param string $filter
     * @param mixed $filterCondition
     * @return $this
     */
    public function addCollectionFilter(Collection $collection, $filter, $filterCondition)
    {
        if (isset($this->aggregation[$filter])) {
            if ($result = array_intersect($this->aggregation[$filter], (array)$filterCondition)) {
                $this->aggregation[$filter] = $result;
            }
        } else {
            $this->aggregation[$filter] = (array)$filterCondition;
        }
        $collection->addFieldToFilter($filter, $this->aggregation[$filter]);

        return $this;
    }
}
