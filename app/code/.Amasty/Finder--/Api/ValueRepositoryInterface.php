<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Finder
 */


namespace Amasty\Finder\Api;

/**
 * @api
 */
interface ValueRepositoryInterface
{
    /**
     * Save
     *
     * @param \Amasty\Finder\Api\Data\ValueInterface $value
     * @return \Amasty\Finder\Api\Data\ValueInterface
     */
    public function save(\Amasty\Finder\Api\Data\ValueInterface $value);

    /**
     * @param $parentId
     * @param $dropdownId
     * @param $name
     *  @return bool
     */
    public function saveValue($parentId, $dropdownId, $name);

    /**
     * @param array $data
     * @return int
     */
    public function saveNewFinder(array $data);

    /**
     * @return \Amasty\Finder\Model\Value
     */
    public function getValueModel();

    /**
     * Get by id
     *
     * @param int $id
     * @return \Amasty\Finder\Api\Data\ValueInterface
     */
    public function getById($id);

    /**
     * @param $parentId
     * @param $dropdownId
     * @return \Amasty\Finder\Model\ResourceModel\Value\Collection
     */
    public function getByParentAndDropdownIds($parentId, $dropdownId);

    /**
     * @param $id
     * @return \Amasty\Finder\Model\Value
     */
    public function getByParentId($id);

    /**
     * @param $newId
     * @param $finderI
     * @return string
     */
    public function getSkuById($newId, $finderI);

    /**
     * Delete
     *
     * @param \Amasty\Finder\Api\Data\ValueInterface $value
     * @return bool true on success
     */
    public function delete(\Amasty\Finder\Api\Data\ValueInterface $value);

    /**
     * Delete by id
     *
     * @param int $id
     * @return bool true on success
     */
    public function deleteById($id);

    /**
     * @param $finder
     * @return bool
     */
    public function deleteOldData($finder);

    /**
     * Lists
     *
     * @return \Amasty\Finder\Api\Data\ValueInterface[] Array of items.
     * @throws \Magento\Framework\Exception\NoSuchEntityException The specified cart does not exist.
     */
    public function getList();
}
