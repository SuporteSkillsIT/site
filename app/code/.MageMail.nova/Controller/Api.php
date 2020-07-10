<?php
namespace KJ\MageMail\Controller;

/**
 * Deprecated API for Magento v1. This has been superseeded by the JsonApi classes.
 */
abstract class Api extends \Magento\Framework\App\Action\Action
{

    /**
     * @var \KJ\MageMail\Helper\Data
     */
    protected $mageMailHelper;

    /**
     * @var \Magento\Framework\App\ResourceConnection
     */
    protected $resourceConnection;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * @var \Magento\SalesRule\Model\RuleFactory
     */
    protected $salesRuleRuleFactory;

    /**
     * @var \Magento\Framework\Data\Collection\AbstractDbFactory
     */
    protected $collectionAbstractDbFactory;

    /**
     * @var \Magento\Framework\Filesystem\Io\FileFactory
     */
    protected $ioFileFactory;

    protected $_jsonHelper;

    protected $couponMassGenerator;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \KJ\MageMail\Helper\Data $mageMailHelper,
        \Magento\Framework\App\ResourceConnection $resourceConnection,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\Json\Helper\Data $jsonHelper,
        \Magento\SalesRule\Model\RuleFactory $salesRuleRuleFactory,
        \Magento\Framework\Data\Collection\AbstractDbFactory $collectionAbstractDbFactory,
        \Magento\Framework\Filesystem\Io\FileFactory $ioFileFactory,
        \Magento\SalesRule\Model\Coupon\Massgenerator $massgenerator
    ) {
        $this->collectionAbstractDbFactory = $collectionAbstractDbFactory;
        $this->ioFileFactory = $ioFileFactory;
        $this->mageMailHelper = $mageMailHelper;
        $this->_jsonHelper = $jsonHelper;
        $this->resourceConnection = $resourceConnection;
        $this->logger = $logger;
        $this->salesRuleRuleFactory = $salesRuleRuleFactory;
        $this->couponMassGenerator = $massgenerator;
        parent::__construct(
            $context
        );
    }

    /**
     * @return \KJ\MageMail\Helper\Data
     */
    protected function help()
    {
        return $this->mageMailHelper;
    }

    protected function _getReadConnection()
    {
        /** @var \Magento\Framework\App\ResourceConnection $resource */
        $resource = $this->resourceConnection;

        return $resource->getConnection('core_read');
    }

    protected function _getWriteConnection()
    {
        /** @var \Magento\Framework\App\ResourceConnection $resource */
        $resource = $this->resourceConnection;

        return $resource->getConnection('core_write');
    }

    protected function _log($message)
    {
        if ($this->help()->logEverything()) {
            $this->logger->info($message);
        }
    }
    protected function _jsonResponse($data)
    {
        $this->getResponse()->setHeader('Content-Type', 'application/json');
        $this->getResponse()->setBody($this->_jsonHelper->jsonEncode($data));
    }

    protected function _authenticate()
    {
        if ($this->getRequest()->getParam('route') != $this->help()->getApiRoute()) {
            $this->norouteAction();
            return false;
        }

        if (!$this->_isAuthenticated()) {
            $this->_jsonResponse(array(
                'success'   => false,
                'message'   => "Authentication failed",
            ));
            return false;
        }

        if ($this->help()->isIpAddressWhitelistEnabled()) {
            $ipAddress = $this->help()->currentIpAddress();
            if (!$ipAddress || ! ($this->help()->isCurrentIpAddressWhiteListed())) {
                $this->_jsonResponse(array(
                    'success'   => false,
                    'message'   => "IP Address isn't whitelisted: $ipAddress",
                ));

                return false;
            }
        }

        $query = $this->getRequest()->getParam('query');
        if ($this->help()->isQueryBlacklisted($query)) {
            $this->_jsonResponse(array(
                'success'   => false,
                'message'   => "Blocked",
            ));

            return false;
        }

        return true;
    }

    protected function _isAuthenticated()
    {
        $enabled = $this->help()->isApiEnabled();
        if (!$enabled) {
            $this->_log("API isn't enabled");
            return false;
        }

        $apiKey = $this->help()->getApiKey();
        if (!$apiKey) {
            $this->_log("No API is setup");
            return false;
        }

        if ($this->getRequest()->getPost('api_key') && $this->getRequest()->getPost('api_key') == $apiKey) {
            $this->_log("Authenticated");
            return true;
        }

        $this->_log("Posted API doesn't match actual API");
        $this->_log("Posted: " . $this->getRequest()->getPost('api_key'));
        $this->_log("Key: " . $apiKey);

        return false;
    }

    protected function _createAheadworksSubscriptionFields()
    {
        $before = microtime(true);
        if (! $this->_authenticate()) {
            return $this;
        }

        if ($this->help()->isSqlApiDisabled()) {
            throw new \Exception("disabled");
        }

        $this->resourceConnection->getConnection()
            ->addColumn($this->resourceConnection->getTableName('aw_sarp/flat_subscriptions'),'magemail_updated_at', array(
                'type'      => \Magento\Framework\Db\Ddl\Table::TYPE_TIMESTAMP,
                'nullable'  => false,
                'default'   => \Magento\Framework\Db\Ddl\Table::TIMESTAMP_INIT_UPDATE,
                'comment'   => 'Magemail Updated At'
            ));

        $response = array(
            'success'           => true,
            'duration_seconds'  => number_format((microtime(true) - $before), 3),
        );

        return $this->_jsonResponse($response);
    }

    /**
     * @param $jsonString
     * @return \Zend_Db_Select
     */
    protected function _jsonToSelect($jsonString)
    {
        $selectArray = json_decode($jsonString, true);

        $select = $this->_getReadConnection()->select();

        $this->_parseFrom($select, $selectArray);
        $this->_parseWhere($select, $selectArray);
        $this->_parseColumns($select, $selectArray);
        $this->_parseGroup($select, $selectArray);
        $this->_parseOrder($select, $selectArray);
        $this->_parseLimit($select, $selectArray);

        return $select;
    }

    /**
     * @param $select Zend_Db_Select
     * @param $selectArray array()
     * @return \Zend_Db_Select
     */
    protected function _parseFrom($select, $selectArray)
    {
        $from = $selectArray['from'];
        foreach ($from as $alias => $details) {
            if ($details['joinType'] == 'from') {
                $select->from(array($alias => $details['tableName']), array());
            }
            if ($details['joinType'] == 'left join') {
                $select->joinLeft(
                    array($alias => $details['tableName']),
                    $details['joinCondition'],
                    array()
                );
            }
        }

        return $this;
    }

    /**
     * @param $select Zend_Db_Select
     * @param $selectArray array()
     * @return \Zend_Db_Select
     */
    protected function _parseWhere($select, $selectArray)
    {
        $where = $selectArray['where'];
        if (empty($where)) {
            return $this;
        }

        $whereSql = implode(" ", $where);
        $select->where($whereSql);

        return $this;
    }

    /**
     * @param $select Zend_Db_Select
     * @param $parts array()
     * @return \Zend_Db_Select
     */
    protected function _parseColumns($select, $parts)
    {
        if (! isset($parts['columns'])) {
            throw new \Exception("Missing columns");
        }

        foreach ($parts['columns'] as $column) {
            $table = isset($column[0]) ? $column[0] : null;
            $field = isset($column[1]) ? $column[1] : null;
            $alias = isset($column[2]) ? $column[2] : null;

            $expression = (is_array($field) && isset($field['expression'])) ? $field['expression'] : null;
            if ($expression) {
                $select->columns(array($alias => new \Zend_Db_Expr($expression)));
            } else {
                $select->columns(array($alias => "$table.$field"));
            }
        }
    }

    /**
     * @param $select Zend_Db_Select
     * @param $parts array()
     * @return \Zend_Db_Select
     */
    protected function _parseGroup($select, $parts)
    {
        if (! isset($parts['group'])) {
            return $this;
        }

        $select->group($parts['group']);
        return $this;
    }

    /**
     * @param $select Zend_Db_Select
     * @param $parts array()
     * @return \Zend_Db_Select
     */
    protected function _parseOrder($select, $parts)
    {
        if (! isset($parts['order'])) {
            return $this;
        }

        $orderParts = array();
        foreach ($parts['order'] as $orderPart) {
            $orderParts[] = implode(" ", $orderPart);
        }

        $select->order($orderParts);
        return $this;
    }

    /**
     * @param $select Zend_Db_Select
     * @param $selectArray array()
     * @return \Zend_Db_Select
     */
    protected function _parseLimit($select, $selectArray)
    {
        if (! isset($selectArray['limit_count'])) {
            throw new \Exception("Missing limit_count");
        }

        $limitCount = $selectArray['limit_count'];
        $limitOffset = isset($selectArray['limit_offset']) ? $selectArray['limit_offset'] : null;
        $select->limit($limitCount, $limitOffset);

        return $this;
    }

    protected function _insertAction()
    {
        $before = microtime(true);
        if (! $this->_authenticate()) {
            return $this;
        }

        if (! $this->help()->isApiWriteAccessEnabled()) {
            return $this->_jsonResponse(array(
                'success'   => false,
                'message'   => "Write access isn't enabled over the API",
            ));
        }

        $query = $this->getRequest()->getParam('query');
        if (!$query) {
            return $this->_jsonResponse(array(
                'success'   => false,
                'message'   => "Missing query",
            ));
        }

        if ($this->help()->logEverything()) {
            $this->_log("Insert query: " . $query);
        }

        $this->_getWriteConnection()->query($query);
        $response = array(
            'success' => true,
            'duration_seconds'  => number_format((microtime(true) - $before), 3),
        );

        return $this->_jsonResponse($response);
    }

    protected function _couponCreate()
    {
        if (! $this->_authenticate()) return $this;
        $ruleId = $this->getRequest()->getParam('rule_id');
        if (! $ruleId) {
            throw new \Exception("Missing rule_id");
        }

        /** @var \Magento\SalesRule\Model\Rule $rule */
        $rule = $this->salesRuleRuleFactory->create();
        $rule->load($ruleId);
        if (! $rule->getId()) {
            throw new \Exception("Couldn't load rule by ID: $ruleId");
        }

        $data = $this->_prepareMassGeneratorData($rule);

        if (!$this->couponMassGenerator->validateData($data)) {
            throw new \Exception("Invalid code pool generator data");
        }

        $this->couponMassGenerator->setData($data);
        $this->couponMassGenerator->generatePool();
        $generatedCount = $this->couponMassGenerator->getGeneratedCount();

        return $this->_jsonResponse(array(
            'success'   => true,
            'amount'    => $generatedCount,
        ));
    }

    /**
     * @param $rule Mage_SalesRule_Model_Rule
     * @return array
     */
    protected function _prepareMassGeneratorData($rule)
    {
        return array(
            'rule_id'           => $rule->getId(),
            'format'            => 'alphanum',
            'dash'              => $this->getRequest()->getParam('dash') ? (int)$this->getRequest()->getParam('dash') : 4,
            'length'            => $this->getRequest()->getParam('length') ? (int)$this->getRequest()->getParam('length') : 12,
            'prefix'            => 'MM-',
            'qty'               => $this->getRequest()->getParam('amount') ? (int)$this->getRequest()->getParam('amount') : 10,
            'uses_per_coupon'   => $rule->getUsesPerCoupon(),
            'uses_per_customer' => $rule->getUsesPerCustomer(),
        );
    }

    protected function _couponExpire()
    {
        if (! $this->_authenticate()) return $this;

        $coupons = $this->_getCouponsAsArray();

        $this->_jsonResponse(array(
            'success' => true,
            'coupons' => $coupons,
        ));

        return $this;
    }

    protected function _getCouponsAsArray()
    {
        $coupons = $this->getRequest()->getParam('coupons');
        if (! $coupons) {
            throw new \Exception("Missing coupons parameter");
        }

        return array_map('trim', explode(',', $coupons));
    }
}
