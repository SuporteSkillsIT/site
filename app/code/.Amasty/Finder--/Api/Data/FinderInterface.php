<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Finder
 */


namespace Amasty\Finder\Api\Data;

interface FinderInterface
{
    /**
     * Constants defined for keys of data array
     */
    const FINDER_ID = 'finder_id';
    const CNT = 'cnt';
    const NAME = 'name';
    const TEMPLATE = 'template';
    const META_TITLE = 'meta_title';
    const META_DESCR = 'meta_descr';
    const CUSTOM_URL = 'custom_url';

    /**
     * @return int
     */
    public function getFinderId();

    /**
     * @param int $finderId
     *
     * @return $this
     */
    public function setFinderId($finderId);

    /**
     * @return int
     */
    public function getCnt();

    /**
     * @param int $cnt
     *
     * @return $this
     */
    public function setCnt($cnt);

    /**
     * @return string
     */
    public function getName();

    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName($name);

    /**
     * @return string
     */
    public function getTemplate();

    /**
     * @param string $template
     *
     * @return $this
     */
    public function setTemplate($template);

    /**
     * @return string
     */
    public function getMetaTitle();

    /**
     * @param string $metaTitle
     *
     * @return $this
     */
    public function setMetaTitle($metaTitle);

    /**
     * @return string
     */
    public function getMetaDescr();

    /**
     * @param string $metaDescr
     *
     * @return $this
     */
    public function setMetaDescr($metaDescr);

    /**
     * @return string
     */
    public function getCustomUrl();

    /**
     * @param string $customUrl
     *
     * @return $this
     */
    public function setCustomUrl($customUrl);
}
