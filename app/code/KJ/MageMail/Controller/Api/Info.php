<?php
namespace KJ\MageMail\Controller\Api;

use KJ\MageMail\Controller\Api;

class Info extends Api
{
    protected $_metadata;
    protected $_moduleList;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \KJ\MageMail\Helper\Data $mageMailHelper,
        \Magento\Framework\App\ResourceConnection $resourceConnection,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\Json\Helper\Data $jsonHelper,
        \Magento\SalesRule\Model\RuleFactory $salesRuleRuleFactory,
        \Magento\Framework\Data\Collection\AbstractDbFactory $collectionAbstractDbFactory,
        \Magento\Framework\Filesystem\Io\FileFactory $ioFileFactory,
        \Magento\Framework\App\ProductMetadataInterface $metadata,
        \Magento\SalesRule\Model\Coupon\Massgenerator $massgenerator,
        \Magento\Framework\Module\ModuleListInterface $moduleList
    )
    {
        parent::__construct($context, $mageMailHelper, $resourceConnection, $logger, $jsonHelper, $salesRuleRuleFactory,
            $collectionAbstractDbFactory, $ioFileFactory, $massgenerator);
        $this->_metadata = $metadata;
        $this->_moduleList = $moduleList;
    }


    public function execute()
    {
        if (! $this->_authenticate()) {
            return $this;
        }

        $this->_jsonResponse(array(
            'success'   => true,
            'version'   => $this->_getExtensionVersion(),
            'magento_version' => $this->_metadata->getEdition() . " " . $this->_metadata->getVersion(),
        ));
    }

    protected function _getExtensionVersion()
    {
        $moduleCode = 'KJ_MageMail';
        $moduleInfo = $this->_moduleList->getOne($moduleCode);
        return $moduleInfo['setup_version'];
    }
}