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

namespace Modulesgarden\Crm\Models\FieldTypes;


use Modulesgarden\Crm\Models\FieldTypes\Source\FieldTypeAbstract;
use Modulesgarden\Crm\Models\FieldTypes\Source\FieldTypeInterface;
use Modulesgarden\Crm\Models\Validators\Common;
use \Exception;


/**
 * this class handle 'Textarea' data field's
 */
class Textarea extends FieldTypeAbstract implements FieldTypeInterface
{
    /**
     * Plain flag for multiple values that could be assigned for this field
     * @var bolean
     */
    protected $multiple = false;

    /**
     * We want to keep validators here
     * @var array
     */
    protected $possibleValidators = array(
        'required',
        'min',
        'max',
        'regex',
    );

    /**
     * if value is provided
     *
     * @return bolean
     */
    public function validateRequired()
    {
        return !empty($this->data);
    }

    /**
     * if data mach minimum string length
     *
     * @return bolean
     */
    public function validateMin()
    {
        $length = intval(array_get($this->config, 'min', 3));

        return ( strlen($this->data) >= $length );
    }


    /**
     * if data mach maximum string length
     *
     * @return bolean
     */
    public function validateMax()
    {
        $length = intval(array_get($this->config, 'max', 3));

        return ( strlen($this->data) >= $length );
    }


    /**
     * if data mach provided regex
     *
     * @return bolean
     */
    public function validateRegex()
    {
        $regex = intval(array_get($this->config, 'regex', '/\\S/'));

        return ( preg_match($regex, $this->data) == 1);
    }


    public function validate($params = array())
    {
        foreach ($this->field->validators as $v)
        {
            if($v->type == 'required' && empty($params)) {
                throw new Exception($v->error);
            } elseif($v->type == 'min') {
                if(!Common::validMinCharacters($params, $v->value)) {
                    throw new Exception($v->error);
                }
            } elseif($v->type == 'max') {
                if(!Common::validMaxCharacters($params, $v->value)) {
                    throw new Exception($v->error);
                }
            } elseif($v->type == 'regex') {
                if(!Common::isValidByRegex($params, sprintf("/%s/", $v->value))) {
                    throw new Exception($v->error);
                }
            }
        }
        return true;
    }


    // also must have is update function
    function setData($newValue)
    {
        if( $this->data != $newValue ) {

            $this->validate($newValue);

            $this->data = $newValue ? $newValue : '';
            $this->save();
        }

        return true;
    }

    // also must have is update function
    function setDataFromMigration(&$field, $newValue)
    {
        $this->data = $newValue;
        $this->save();

        return true;
    }

    // get value
    function getData()
    {
        return $this->data;
    }
}
