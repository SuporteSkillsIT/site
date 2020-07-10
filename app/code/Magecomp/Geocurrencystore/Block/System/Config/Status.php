<?php
namespace Magecomp\Geocurrencystore\Block\System\Config;

use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Data\Form\Element\AbstractElement;
use Magecomp\Geocurrencystore\Helper\Data;

class Status extends Field
{
    protected $helper;
    public function __construct(
        \Magento\Framework\Filesystem $_filesystem,
        Data $helper
    )
    {
        $this->_filesystem = $_filesystem;
        $this->helper = $helper;
    }

    public function render( AbstractElement $element)
    {
        $element->unsScope()->unsCanUseWebsiteValue()->unsCanUseDefaultValue();
        return parent::render($element);
    }

    protected function _getElementHtml(AbstractElement $element)
    {
        $filename = $this->helper->getDatabaseDirectory()."/geoip/GeoIP.dat";
        if (file_exists($filename)) {
            $modify="Magecomp/Geocurrencystore/view/frontend/web/database/geoip/GeoIP.dat was last modified: </br> " . date ("F d Y H:i:s.", filemtime($filename));
        }
        return '<div id="sync_update_date">' .$modify. '</div>';
    }
}