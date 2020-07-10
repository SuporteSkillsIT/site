<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Finder
 */

/**
 * Copyright © 2015 Amasty. All rights reserved.
 */
namespace Amasty\Finder\Model\ResourceModel\Value;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(\Amasty\Finder\Model\Value::class, \Amasty\Finder\Model\ResourceModel\Value::class);
    }

    /**
     * @param \Amasty\Finder\Model\Finder $finder
     * @return $this
     */
    public function joinAllFor(\Amasty\Finder\Model\Finder $finder)
    {
        $select = $this->getSelect();
        $select->reset(\Zend_Db_Select::FROM);
        $select->reset(\Zend_Db_Select::COLUMNS);

        $pos = 0;
        foreach ($finder->getDropdowns() as $d) {
            $pos = $d->getPos();

            $table = ["d" . $pos => $this->getTable('amasty_finder_value')];
            $fields = ["name" . $pos => "d" . $pos . ".name"];
            if (0 == $pos) {
                $select->from($table, $fields);
                $select->where("d" . $pos . ".dropdown_id=?", $d->getId());
            } else {
                $bind = "d" . $pos . ".parent_id = d" . ($pos - 1) . ".value_id";
                $select->joinInner($table, $bind, $fields);
            }
        }

        $select->joinInner(
            ['finderMap' => $this->getTable('amasty_finder_map')],
            "d" . $pos . ".value_id = finderMap.value_id",
            [
                'sku',
                'val' => 'finderMap.value_id',
                'vid' => 'finderMap.id',
                'finder_id' => new \Zend_Db_Expr($finder->getId())
            ]
        );

        return $this;
    }
}
