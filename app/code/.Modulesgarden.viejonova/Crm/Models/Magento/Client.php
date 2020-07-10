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
 * @author      Paweł Tomczyk <pawel.tom@modulesgarden.com> /
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

namespace Modulesgarden\Crm\Models\Magento;

use \Illuminate\Database\Eloquent\Model as EloquentModel;

class Client extends EloquentModel
{

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'entity_id';
    public $timestamps = false;
    protected $table = 'customer_entity';
    protected $visible = array('entity_id', 'firstname', 'lastname', 'email');
    protected $select = array('entity_id', 'firstname', 'lastname', 'email');
    protected $fillable = array(
        'entity_id',
        'firstname',
        'lastname',
        'email'
    );

    public function getIdAttribute($value)
    {
        return intval($value);
    }

    public function getFullNameAttribute()
    {
        return $this->firstname . ' ' . $this->lastname;
    }

    // manually clear visible parameter since here wa want all data parsed to and available
    public function markAllColumnsVisible()
    {
        $this->visible = array();
        return $this;
    }

}
