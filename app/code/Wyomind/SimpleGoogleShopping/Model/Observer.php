<?php
/**
 * Copyright © 2020 Wyomind. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Wyomind\SimpleGoogleShopping\Model;

/**
 * Simple Google Shopping observer
 */
class Observer
{
    /**
     * @var ResourceModel\Feeds\CollectionFactory
     */
    protected $_collectionFactory = null;

    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $objectManager;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $coreDate = null;

    /**
     * @var \Magento\Framework\Mail\Template\TransportBuilder
     */
    protected $_transportBuilder;

    /**
     * @var \Wyomind\SimpleGoogleShopping\Logger\LoggerCron
     */
    protected $_logger = null;

    /**
     * @var \Wyomind\Framework\Helper\License
     */
    protected $licenseHelper = null;

    /**
     * @param ResourceModel\Feeds\CollectionFactory $collectionFactory
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     * @param \Magento\Framework\Stdlib\DateTime\DateTime $coreDate
     * @param \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder
     * @param \Wyomind\Framework\Helper\License $licenseHelper
     */
    public function __construct(
        \Wyomind\SimpleGoogleShopping\Model\ResourceModel\Feeds\CollectionFactory $collectionFactory,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Magento\Framework\Stdlib\DateTime\DateTime $coreDate,
        \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder,
        \Wyomind\Framework\Helper\License $licenseHelper
    )
    {
        $this->_collectionFactory = $collectionFactory;
        $this->coreDate = $coreDate;
        $this->_transportBuilder = $transportBuilder;
        $this->_logger = $objectManager->create('Wyomind\SimpleGoogleShopping\Logger\LoggerCron');
        $this->licenseHelper = $licenseHelper;
    }

    /**
     * Check if google shopping data feeds need to be generated
     * @param \Magento\Cron\Model\Schedule $schedule
     */
    public function checkToGenerate(\Magento\Cron\Model\Schedule $schedule)
    {
        try {
            $log = [];

            $this->_logger->notice("-------------------- CRON PROCESS --------------------");
            $log[] = "-------------------- CRON PROCESS --------------------";

            $coll = $this->_collectionFactory->create();
            $cnt = 0;
            $first = true;

            foreach ($coll as $feed) {
                $done = false;
                try {
                    $feed->isCron = true;
                    if ($first) {
                        $feed->loadCustomFunctions();
                        $first = false;
                    }

                    $this->_logger->notice("--> Running profile : " . $feed->getSimplegoogleshoppingFilename() . " [#" . $feed->getSimplegoogleshoppingId() . "] <--");
                    $log[] = "--> Running profile : " . $feed->getSimplegoogleshoppingFilename() . " [#" . $feed->getSimplegoogleshoppingId() . "] <--";

                    $cron = [];

                    $cron['current']['localDate'] = $this->coreDate->date('l Y-m-d H:i:s');
                    $cron['current']['gmtDate'] = $this->coreDate->gmtDate('l Y-m-d H:i:s');
                    $cron['current']['localTime'] = $this->coreDate->timestamp();
                    $cron['current']['gmtTime'] = $this->coreDate->gmtTimestamp();

                    $cron['file']['localDate'] = $this->coreDate->date('l Y-m-d H:i:s', $feed->getSimplegoogleshoppingTime());
                    $cron['file']['gmtDate'] = $feed->getSimplegoogleshoppingTime();
                    $cron['file']['localTime'] = $this->coreDate->timestamp($feed->getSimplegoogleshoppingTime());
                    $cron['file']['gmtTime'] = strtotime($feed->getSimplegoogleshoppingTime());

                    $cron['offset'] = $this->coreDate->getGmtOffset("hours");

                    $log[] = '   * Last update : ' . $cron['file']['gmtDate'] . " GMT / " . $cron['file']['localDate'] . ' GMT' . $cron['offset'] . "";
                    $log[] = '   * Current date : ' . $cron['current']['gmtDate'] . " GMT / " . $cron['current']['localDate'] . ' GMT' . $cron['offset'] . "";
                    $this->_logger->notice('   * Last update : ' . $cron['file']['gmtDate'] . " GMT / " . $cron['file']['localDate'] . ' GMT' . $cron['offset']);
                    $this->_logger->notice('   * Current date : ' . $cron['current']['gmtDate'] . " GMT / " . $cron['current']['localDate'] . ' GMT' . $cron['offset']);

                    $cronExpr = json_decode($feed->getCronExpr());

                    $i = 0;

                    if ($cronExpr != null && isset($cronExpr->days)) {
                        foreach ($cronExpr->days as $d) {
                            foreach ($cronExpr->hours as $h) {
                                $time = explode(':', $h);
                                if (date('l', $cron['current']['gmtTime']) == $d) {
                                    $cron['tasks'][$i]['localTime'] = strtotime($this->coreDate->date('Y-m-d')) + ($time[0] * 60 * 60) + ($time[1] * 60);
                                    $cron['tasks'][$i]['localDate'] = date('l Y-m-d H:i:s', $cron['tasks'][$i]['localTime']);
                                } else {
                                    $cron['tasks'][$i]['localTime'] = strtotime("last " . $d, $cron['current']['localTime']) + ($time[0] * 60 * 60) + ($time[1] * 60);
                                    $cron['tasks'][$i]['localDate'] = date('l Y-m-d H:i:s', $cron['tasks'][$i]['localTime']);
                                }

                                if ($cron['tasks'][$i]['localTime'] >= $cron['file']['localTime'] && $cron['tasks'][$i]['localTime'] <= $cron['current']['localTime'] && $done != true) {
                                    $this->_logger->notice('   * Scheduled : ' . ($cron['tasks'][$i]['localDate'] . " GMT" . $cron['offset']));
                                    $log[] = '   * Scheduled : ' . ($cron['tasks'][$i]['localDate'] . " GMT" . $cron['offset']) . "";
                                    $this->_logger->notice("   * Starting generation");
                                    $result = $feed->generateXml();
                                    if ($result === $feed) {
                                        $done = true;
                                        $this->_logger->notice("   * EXECUTED!");
                                        $log[] = "   * EXECUTED!";
                                    } else {
                                        $this->_logger->notice("   * ERROR! " . $result);
                                        $log[] = "   * ERROR! " . $result . "";
                                    }
                                    $cnt++;
                                    break 2;
                                }

                                $i++;
                            }
                        }
                    }
                } catch (\Exception $e) {
                    $cnt++;
                    $this->_logger->notice("   * ERROR! " . ($e->getMessage()));
                    $log[] = "   * ERROR! " . ($e->getMessage()) . "";
                }
                if (!$done) {
                    $this->_logger->notice("   * SKIPPED!");
                    $log[] = "   * SKIPPED!";
                }
            }

            if ($this->licenseHelper->getStoreConfig("simplegoogleshopping/settings/enable_reporting")) {
                $emails = explode(',', $this->licenseHelper->getStoreConfig("simplegoogleshopping/settings/emails"));
                if (count($emails) > 0) {
                    try {
                        if ($cnt) {
                            $template = "wyomind_simplegoogleshopping_cron_report";

                            $transport = $this->_transportBuilder
                                ->setTemplateIdentifier($template)
                                ->setTemplateOptions(
                                    [
                                        'area' => \Magento\Backend\App\Area\FrontNameResolver::AREA_CODE,
                                        'store' => \Magento\Store\Model\Store::DEFAULT_STORE_ID
                                    ]
                                )
                                ->setTemplateVars(
                                    [
                                        'report' => implode("<br/>", $log),
                                        'subject' => $this->licenseHelper->getStoreConfig('simplegoogleshopping/settings/report_title')
                                    ]
                                )
                                ->setFrom(
                                    [
                                        'email' => $this->licenseHelper->getStoreConfig('simplegoogleshopping/settings/sender_email'),
                                        'name' => $this->licenseHelper->getStoreConfig('simplegoogleshopping/settings/sender_name')
                                    ]
                                )
                                ->addTo($emails[0]);

                            $count = count($emails);
                            for ($i = 1; $i < $count; $i++) {
                                $transport->addCc($emails[$i]);
                            }

                            $transport->getTransport()->sendMessage();
                        }
                    } catch (\Exception $e) {
                        $this->_logger->notice('   * EMAIL ERROR! ' . $e->getMessage());
                        $log[] = '   * EMAIL ERROR! ' . ($e->getMessage());
                    }
                }
            }
        } catch (\Exception $e) {
            $schedule->setStatus('failed');
            $schedule->setMessage($e->getMessage());
            $schedule->save();
            $this->_logger->notice("MASSIVE ERROR ! ");
            $this->_logger->notice($e->getMessage());
        }
    }
}