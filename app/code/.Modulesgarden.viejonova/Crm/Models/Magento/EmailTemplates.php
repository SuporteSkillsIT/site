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
use \Illuminate\Database\Capsule\Manager as DB;

class EmailTemplates extends EloquentModel
{

    public $timestamps = false;
    protected $table = 'email_template';
    protected $guarded = array('template_id');
    protected $primaryKey = 'template_id';
    protected $fillable = array(
        'template_code',
        'template_text',
        'template_styles',
        'template_type',
        'template_subject',
        'template_sender_name',
        'template_sender_email',
        'added_at',
        'modified_at',
        'orig_template_code',
        'orig_template_variables',
    );

    public function scopeForSelect($query)
    {
        return $query->select(array(
                    'template_id',
                    'template_code',
                    DB::raw("CONCAT('#', template_id, ' ', template_code) as full"),
        ));
    }

    public function scopeOnlyCrmType($query)
    {
        return $query->where('template_code', 'LIKE', 'crm_%');
    }

    public function getIdAttribute($value)
    {
        return intval($value);
    }

    /**
     * Scope for easy search global (by type, name or subject)
     *
     * @return Eloquent\Query\Builder
     */
    public function scopeSearchGlobal($query, $str)
    {
        return $query->where(function($query) use($str) {
                    return $query->orWhere('template_code', 'like', "%{$str}%")
                                    ->orWhere('template_subject', 'like', "%{$str}%");
                });
    }

}
