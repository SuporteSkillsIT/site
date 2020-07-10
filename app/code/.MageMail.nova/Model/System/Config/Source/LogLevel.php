<?php
namespace KJ\MageMail\Model\System\Config\Source;


class LogLevel extends \Magento\Framework\DataObject
{
    const LEVEL_NOTHING = 0;
    const LEVEL_EVERYTHING = 1;
    public function __construct(
        array $data = []
    ) {
        parent::__construct(
            $data
        );
    }


    public function toOptionArray()
    {
        return array(
            array(
                'value' => self::LEVEL_NOTHING,
                'label' => "Don't Log Anything"
            ),
            array(
                'value' => self::LEVEL_EVERYTHING,
                'label' => "Log Everything"
            ),
        );
    }
}
