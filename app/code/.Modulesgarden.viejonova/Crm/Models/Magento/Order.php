<?php
/***************************************************************************************
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
 **************************************************************************************/


namespace Modulesgarden\Crm\Models\Magento;


use \Illuminate\Database\Eloquent\Model as EloquentModel;

class Order extends EloquentModel
{
    /**
     * turn off timestamps for this table
     */
    public $timestamps = false;

    /**
     * Set up Table
     */
    protected $table = 'sales_order';


    /**
     * Columns to select by default for this model
     *
     * @var array
     */
//    protected $select  = array(
//        'id',
//        'ordernum',
//        'userid',
//        'contactid',
//        'date',
//        'amount',
//        'paymentmethod',
//        'invoiceid',
//        'status',
//        'invoiceid',
//        'notes',
//    );

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $fillable = array(
        'entity_id',
        'state',
        'status',
        'customer_id',
    );

    /**
     * Getter parse value to integer
     */
    public function getUseridAttribute($value)
    {
        return intval($value);
    }

    /**
     * Getter parse value to integer
     */
    public function getContactidAttribute($value)
    {
        return intval($value);
    }


    /**
     * Relation For Assigned Admin to this Resource
     *
     * relation: ONE TO ONE
     *
     * @return mgCRM2\Models\Resource
     */
    public function client()
    {
        return $this->belongsTo('Modulesgarden\Crm\Models\Magento\Client', 'customer_id');
    }


    /**
     * Getter parse value to integer
     */
    public function getInvoiceidAttribute($value)
    {
        return intval($value);
    }

    public function scopeWithClient($query) {
        return $query->with('client');
    }

    public function scopeWhereClient($query, $id) {
        return $query->where('customer_id', '=', $id);
    }
}
