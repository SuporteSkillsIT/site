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


use \Modulesgarden\Crm\Models\FieldTypes\Source\FieldTypeAbstract;
use \Modulesgarden\Crm\Models\FieldTypes\Source\FieldTypeInterface;
use \Modulesgarden\Crm\Models\FieldOption;
use \Modulesgarden\Crm\Models\FieldDataOption;
use \Exception;

/**
 * this class handle 'Text' data field's
 */
class Radio extends FieldTypeAbstract implements FieldTypeInterface
{
    /**
     * Plain flag for multiple values that could be assigned for this field
     * @var bolean
     */
    protected $multiple = true;

    /**
     * We want to keep validators here
     * @var array
     */
    protected $possibleValidators = array(
        'required',
        'min',
        'max',
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




    public function validate($params = array())
    {
        foreach ($this->field->validators as $v)
        {
            if($v->type == 'required' && empty($params)) {
                throw new Exception('Check at least single option');
            }
        }

        return true;
    }

    /**
     * Set data
     *
     * @param type $newValue
     * @return boolean if there was no errors
     * @throws Exception as there are bad params
     */
    function setData($newValue)
    {
        if( is_array($newValue) ) {
            $options = $newValue;
        } elseif(is_numeric($newValue) ) {
            $options = array($newValue);
        } else {
            throw new Exception('Invalid value for checkbox data type');
        }

        $this->validate($options);

        // save this model to db
        $this->save();
        // now we have id to save related options selected
        $this->options()->delete();

        if(is_array($options) && !empty($options))
        {
            $fieldOptions = FieldOption::whereIn('id', $options)->get();

            $optionsToAssociate   = array();
            foreach ($fieldOptions as $option)
            {
                $optionsToAssociate[] = new FieldDataOption(
                    array(
                        'option_id' => $option['id'],
                    )
                );
            }
        }


        if(!empty($optionsToAssociate)) {
            $this->options()->saveMany($optionsToAssociate);
        }

        return true;
    }

    // also must have is update function
    function setDataFromMigration(&$field, $newValue)
    {
        // search if there is option that I want
        $found = false;
        foreach ($field->options as $option)
        {
            if( strpos($option->value, $newValue) !== false ) {
                $found = $option;
            }
        }

        // non existing option for field > create new
        if($found === false)
        {
            $found = new FieldOption(array(
                'field_id'  => $field->id,
                'value'     => $newValue,
            ));
            $found->save();
            $field->load('options');
        }

        if($found->id)
        {
            $optionToAttch = new FieldDataOption(
                array(
                    'option_id' => $found->id,
                )
            );

            // save this model to db
            $this->save();
            // now we have id to save related options selected
            $this->options()->save($optionToAttch);
        }

        return true;
    }
    // get value
    function getData()
    {
        return $this->data;
    }
}
