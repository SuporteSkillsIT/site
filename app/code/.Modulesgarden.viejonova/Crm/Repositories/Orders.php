<?php

/* * *************************************************************************************
 *
 *
 *                  ██████╗██████╗ ███╗   ███╗         Customer
 *                 ██╔════╝██╔══██╗████╗ ████║         Relations
 *                 ██║     ██████╔╝██╔████╔██║         Manager
 *                 ██║     ██╔══██╗██║╚██╔╝██║
 *                 ╚██████╗██║  ██║██║ ╚═╝ ██║         For Magento
 *                  ╚═════╝╚═╝  ╚═╝╚═╝     ╚═╝
 *
 *
 * @author      Piotr Sarzyński <piotr.sa@modulesgarden.com> / < >
 *
 *
 * @link        http://www.docs.modulesgarden.com/CRM_For_WHMCS for documenation
 * @link        http://modulesgarden.com ModulesGarden
 *              Top Quality Custom Software Development
 * @copyright   Copyright (c) ModulesGarden, INBS Group Brand,
 *              All Rights Reserved (http://modulesgarden.com)
 *
 * This software is furnished under a license and mxay be used and copied only  in
 * accordance  with  the  terms  of such  license and with the inclusion of the above
 * copyright notice.  This software  or any other copies thereof may not be provided
 * or otherwise made available to any other person.  No title to and  ownership of
 * the  software is hereby transferred.
 *
 * ************************************************************************************ */

namespace Modulesgarden\Crm\Repositories;

use \Illuminate\Database\Capsule\Manager as DB;
use Modulesgarden\Crm\Repositories\Source\AbstractRepository;
use Modulesgarden\Crm\Repositories\Source\RepositoryInterface;
use Modulesgarden\Crm\Models\Magento\Client;
use Modulesgarden\Crm\Models\Resource;

/**
 * Just container for Orders
 * as repository pattern
 */
class Orders extends AbstractRepository implements RepositoryInterface
{

    /**
     * Determinate model used by this Repository
     *
     * @return Modulesgarden\Crm\Models\Note
     */
    function determinateModel()
    {
        return 'Modulesgarden\Crm\Models\Magento\Order';
    }

    /**
     * Handle Smart table requests for backend
     *
     * @param type  $resourceID related resource
     * @param array $data       params sended by smart table
     * @return type             array for smart table format later parsed to json
     */
    public function parseForTable($resourceID, array $data = array())
    {
        // get me resource
        $resource = Resource::withTrashed()->findOrFail($resourceID);
        $client = Client::findOrFail($resource->client_id);

        // limit
        $limit = array_get($data, 'params.pagination.number', 10);
        $ofset = array_get($data, 'params.pagination.start', 0);

        // order
        $orderBy = array_get($data, 'params.sort.predicate', 'date');
        $orderDesc = array_get($data, 'params.sort.reverse', true);
        $orderDesc = ($orderDesc === true) ? 'DESC' : 'ASC';

        // search
        $message = array_get($data, 'params.search.predicateObject.message', false);
        $date = array_get($data, 'params.search.predicateObject.date', false);
        $admin_id = array_get($data, 'params.search.predicateObject.admin_id', false);
        $event = array_get($data, 'params.search.predicateObject.event', false);

        // global search
        $search = array_get($data, 'params.search.predicateObject', false);
        $searchGlobal = array_pull($search, '$', false);

        // define possibles search
        $searchColumn = array(
            'id' => array_get($data, 'params.search.predicateObject.id', false),
            'ordernum' => array_get($data, 'params.search.predicateObject.ordernum', false),
            'date' => array_get($data, 'params.search.predicateObject.date', false),
            'amount' => array_get($data, 'params.search.predicateObject.amount', false),
            'status' => array_get($data, 'params.search.predicateObject.status', false),
            'paymentmethod' => array_get($data, 'params.search.predicateObject.paymentmethod', false),
            'invoicestatus' => array_get($data, 'params.search.predicateObject.invoicestatus', false),
            'invoiceid' => array_get($data, 'params.search.predicateObject.invoiceid', false),
        );


        // base query with limits etc to obrain what we need
        $query = $this->getModel()
                ->where('sales_order.customer_id', '=', $client->entity_id)
                ->leftJoin('sales_invoice', function($join) {
                    $join->on('sales_invoice.order_id', '=', 'sales_order.entity_id');
                })
                ->leftJoin('sales_order_payment', function($join) {
                    $join->on('sales_order.entity_id', '=', 'sales_order_payment.parent_id');
                })
                ->addSelect(array(
            'sales_order.entity_id',
            'sales_order.increment_id',
            'sales_order.customer_id',
            'sales_order.created_at',
            'sales_order.grand_total',
            'sales_order_payment.method',
            'sales_invoice.entity_id as invoiceid',
            'sales_invoice.state as invoicestatus',
            'sales_order.status',
            'sales_order_payment.method as gateway',
        ));

        // search
        foreach ($searchColumn as $column => $val) {
            if (!empty($val) && $val !== false) {
                if ($column == 'paymentmethod') {
                    $prefix = 'sales_order_payment.method';
                } elseif($column == 'invoicestatus') {
                    $prefix = 'sales_invoice.state';
                } elseif ($column == 'invoiceid') {
                    $prefix = 'sales_invoice.entity_id';
                } elseif ($column == 'id') {
                    $prefix = 'sales_order.entity_id';
                } elseif ($column == 'ordernum') {
                    $prefix = 'sales_order.increment_id';
                } elseif ($column == 'date') {
                    $prefix = 'sales_order.created_at';
                } elseif ($column == 'amount') {
                    $prefix = 'sales_order.grand_total';
                } elseif ($column == 'status') {
                    $prefix = 'sales_order.status';
                }

                $query = $query->where($prefix, 'LIKE', "%{$val}%");
            }
        }

        if (!empty($searchGlobal) && $searchGlobal !== false) {
            $query = $query->where(function($query) use($searchGlobal) {
                foreach (array('sales_order.entity_id', 'sales_order.increment_id', 'sales_order.created_at', 'sales_order.grand_total', 'sales_order_payment.method',
            'sales_order.status', 'sales_invoice.entity_id',) as $c) {
                    $query->orWhere($c, 'LIKE', "%{$searchGlobal}%");
                }
            });
        }

        // basically the same query but no orderby/limit/select
        $total = $query->count();

        if ($orderBy == 'paymentmethod') {
            $orderBy = 'sales_order_payment.method';
        } elseif ($orderBy == 'invoicestatus') {
            $orderBy = 'sales_invoice.state';
        } elseif ($orderBy == 'invoiceid') {
            $orderBy = 'sales_invoice.entity_id';
        } elseif ($orderBy == 'id') {
            $orderBy = 'sales_order.entity_id';
        } elseif ($orderBy == 'ordernum') {
            $orderBy = 'sales_order.increment_id';
        } elseif ($orderBy == 'date') {
            $orderBy = 'sales_order.created_at';
        } elseif ($orderBy == 'amount') {
            $orderBy = 'sales_order.grand_total';
        } elseif ($orderBy == 'status') {
            $orderBy = 'sales_order.status';
        }

        // run this damm queries
        $results = $query->orderBy($orderBy, $orderDesc)->take($limit)->offset($ofset)->get();
        // gather to data format for smart table
        $return = array(
            'data' => $results->toArray(),
            'total' => $total,
        );

        return $return;
    }

}
