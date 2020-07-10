<?php

namespace BoostMyShop\Rma\Model\Rma;

use Magento\Sales\Model\Order\Email\Sender\CreditmemoSender;
use Magento\Framework\DataObject;

class Refund extends DataObject
{
    protected $_creditMemoFactory;
    protected $_creditMemoManagement;
    protected $_creditmemoSender;
    protected $_transaction;
    protected $eventManager;
    protected $_config;

    public function __construct(
        \Magento\Sales\Model\Order\CreditmemoFactory $creditmemoFactory,
        \Magento\Sales\Api\CreditmemoManagementInterface $creditMemoManagement,
        CreditmemoSender $creditmemoSender,
        \Magento\Framework\Event\ManagerInterface $eventManager,
        \Magento\Framework\DB\Transaction $transaction,
        \BoostMyShop\Rma\Model\Config $config
    ){
        $this->_creditMemoManagement = $creditMemoManagement;
        $this->_transaction = $transaction;
        $this->_creditmemoSender = $creditmemoSender;
        $this->_creditMemoFactory = $creditmemoFactory;
        $this->eventManager = $eventManager;
        $this->_config = $config;
    }

    public function process($rma, $data)
    {
        $order = $rma->getOrder();
        $invoice = false;
        foreach($order->getInvoiceCollection() as $item)
            $invoice = $item;

        //fix for Magento bug : https://github.com/magento/magento2/issues/10982
        if ($this->_config->getCreateCreditmemoAgainstOrders())
        {
            $creditmemo = $this->_creditMemoFactory->createByOrder($order, $data);
        }
        else
        {
            if (!$invoice)
                $creditmemo = $this->_creditMemoFactory->createByOrder($order, $data);
            else
                $creditmemo = $this->_creditMemoFactory->createByInvoice($invoice, $data);
        }

        if (!$creditmemo)
            throw new \Exception('Unable to create credit memo.');

        $this->eventManager->dispatch(
            'adminhtml_sales_order_creditmemo_register_before',
            ['creditmemo' => $creditmemo, 'input' => $this->getCreditmemo()]
        );

        $creditmemo->addComment($data['comment_text'], false, false);

        $refundOffline = false;
        $creditmemo->addComment('Refund method : '.($refundOffline ? 'offline' : 'online'), false, false);

        $this->_creditMemoManagement->refund($creditmemo, $refundOffline);

        $this->_creditmemoSender->send($creditmemo);

        return $creditmemo;
    }

}