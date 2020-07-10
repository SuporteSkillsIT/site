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


namespace Modulesgarden\Crm\Models;


use Modulesgarden\Crm\Models\Source\AbstractModel;

class FieldOption extends AbstractModel
{
    public $timestamps = false;

    protected $table = 'crm_fields_options';

    protected $guarded = array('id');

    protected $fillable = array('field_id', 'value');


    /**
     * Just eloquent relation
     *
     * @return type
     */
    public function field()
    {
        return $this->belongsTo('Modulesgarden\Crm\Models\Field', 'field_id');
    }


    public function updateValue($newValue)
    {
        if( is_string($newValue) && !empty($newValue) && $newValue != $this->value ) {
            $this->value = $newValue;
            $this->save();
        }
    }

}
