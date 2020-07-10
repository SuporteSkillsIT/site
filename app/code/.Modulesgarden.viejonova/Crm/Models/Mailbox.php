<?php
/***************************************************************************************
 *
 *
 *                  ██████╗██████╗ ███╗   ███╗         Customer
 *                 ██╔════╝██╔══██╗████╗ ████║         Relations
 *                 ██║     ██████╔╝██╔████╔██║         Manager
 *                 ██║     ██╔══██╗██║╚██╔╝██║
 *                 ╚██████╗██║  ██║██║ ╚═╝ ██║         For WHMCS
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


namespace Modulesgarden\Crm\Models;

use \Illuminate\Database\Capsule\Manager as DB;
use Modulesgarden\Crm\Models\Source\AbstractModel;

class Mailbox extends AbstractModel
{
    public $timestamps = false;

    protected $table = 'crm_mail_configuration';

    protected $fillable = array(
        'name',
        'description',
        'email',
        'MailEncoding',
        'SMTPHost',
        'SMTPPort',
        'SMTPUsername',
        'SMTPPassword',
        'SMTPSSL',
    );


    public function scopeFilterIrrelevantParams($query)
    {
        return $query->select(array(
            $this->primaryKey,
            'name', 
            'description', 
            'email', 
            DB::raw("CONCAT('#', " . $this->primaryKey . ", ' ', name) as full"),
            DB::raw("CONCAT('#', " . $this->primaryKey . ", ' ', name, '(', email, ')') as fullemail"),
        ));
    }
    
    
    /**
     * Scope for easy search global (by name, description or email)
     *
     * @return Eloquent\Query\Builder
     */
    public function scopeSearchGlobal($query, $str)
    {
        return $query->where(function($query) use($str) {
            return $query
                    ->orWhere('name', 'like', "%{$str}%")
                    ->orWhere('email', 'like', "%{$str}%")
                    ->orWhere('description', 'like', "%{$str}%");
        });
    }
}
