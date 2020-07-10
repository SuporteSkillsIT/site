<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Finder
 */

/**
 * Copyright © 2015 Amasty. All rights reserved.
 */
namespace Amasty\Finder\Model\ResourceModel;

use Amasty\Finder\Api\DropdownRepositoryInterface;
use Amasty\Finder\Api\FinderRepositoryInterface;

class Value extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * @var FinderRepositoryInterface
     */
    private $finderRepository;

    /**
     * @var DropdownRepositoryInterface
     */
    private $dropdownRepository;
    /**
     * Model Initialization
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('amasty_finder_value', 'value_id');
    }

    /**
     * Value constructor.
     * @param FinderRepositoryInterface $finderRepository
     * @param DropdownRepositoryInterface $dropdownRepository
     * @param \Magento\Framework\Model\ResourceModel\Db\Context $context
     * @param null $connectionName
     */
    public function __construct(
        \Amasty\Finder\Api\FinderRepositoryInterface $finderRepository,
        \Amasty\Finder\Api\DropdownRepositoryInterface $dropdownRepository,
        \Magento\Framework\Model\ResourceModel\Db\Context $context,
        $connectionName = null
    ) {
        $this->finderRepository = $finderRepository;
        $this->dropdownRepository = $dropdownRepository;
        parent::__construct($context, $connectionName);
    }

    /**
     * @param array $data
     * @return int
     */
    public function saveNewFinder(array $data)
    {
        $connection = $this->getConnection();

        $insertData = [];
        $parentId = 0;
        foreach ($data as $element => $value) {
            if (substr($element, 0, 6) == 'label_') {
                $insertData[] = ['dropdown_id' => substr($element, 6), 'name' => $value];
            }
        }

        foreach ($insertData as $key => $row) {
            $name[$key] = $row['name'];
            $dropdownId[$key] = $row['dropdown_id'];
        }
        array_multisort($dropdownId, SORT_ASC, $name, SORT_ASC, $insertData);

        foreach ($insertData as $insertElement) {
            $connection->insertOnDuplicate($this->getTable('amasty_finder_value'), [
                'parent_id' => $parentId,
                'dropdown_id' => $insertElement['dropdown_id'],
                'name' => $insertElement['name']
            ]);
            $select = $connection->select();
            $select->from($this->getTable('amasty_finder_value'))
                ->where('dropdown_id =?', $insertElement['dropdown_id'])
                ->where('parent_id =?', $parentId)
                ->where('name =?', $insertElement['name']);
            $result = $this->getConnection()->fetchRow($select);

            $parentId = $result['value_id'];
        }
        $connection->insertOnDuplicate($this->getTable('amasty_finder_map'), [
            'value_id' => $parentId,
            'sku' => $data['sku']
        ]);

        $this->finderRepository->updateLinks();
        $dropdown = $this->dropdownRepository->getById($insertElement['dropdown_id']);
        $finderId = $dropdown->getFinderId();

        return $finderId;

    }

    /**
     * @param $newId
     * @param $finderId
     * @return string
     */
    public function getSkuById($newId, $finderId)
    {
        $connection = $this->getConnection();
        $selectSql = $connection->select()
            ->from($this->getTable('amasty_finder_map'))->where('value_id = ?', $finderId)->where('id = ?', $newId);
        $result = $connection->fetchRow($selectSql);
        return $result['sku'];
    }
}
