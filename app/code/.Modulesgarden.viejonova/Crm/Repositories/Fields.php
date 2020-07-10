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


namespace Modulesgarden\Crm\Repositories;


use Modulesgarden\Crm\Repositories\Source\AbstractRepository;
use Modulesgarden\Crm\Repositories\Source\RepositoryInterface;

use Modulesgarden\Crm\Models\Field;
use Modulesgarden\Crm\Models\FieldGroup;
use Modulesgarden\Crm\Models\Resource;
use Modulesgarden\Crm\Models\FieldValidatorConfig;
use \Exception;

/**
 * Just container for Fields
 * as repository pattern
 */
class Fields extends AbstractRepository implements RepositoryInterface
{
    /**
     * Determinate model used by this Repository
     *
     * @return Field
     */
    function determinateModel() {
        return 'Modulesgarden\Crm\Models\Field';
    }

    
    /**
     * Reorder all groups in DB
     *
     * @param array $newOrder with new order syntax id => new order value
     * @return bolean
     * @throws Exception
     */
    public function reorder(array $newOrder)
    {
        // plain check
        if( empty($newOrder) ) {
            throw new Exception('Wrong order parameters provided');
        }

        $fields = $this->model->all();

        foreach ($fields as $field)
        {
            // parameters
            $order         = array_get($newOrder, "{$field->id}.order", 0);
            $groupID       = array_get($newOrder, "{$field->id}.group", null);

            // set order
            $field->order   = $order;

            // check related group if exist and was changed
            if( is_numeric($groupID) && $groupID !== null && intval($field->group_id) != $groupID )
            {
                $group = FieldGroup::find($groupID);

                if( ! $group) {
                    throw new Exception("Could not find Field Group with ID {$groupID}");
                }

                // update relation
                $field->group()->associate($group);
            }


            $field->save();
        }

        return true;
    }


    /**
     * Wrapper to handle udpate only certain parameters for model
     * usefull when we dont want to push every single parameter
     * Find model, and trigger correct method
     *
     * @param type $id
     * @param type $data
     * @return \Modulesgarden\Crm\Models\Field
     * @throws Exception
     */
    public function updateSingleParamInModel($id, $data)
    {
        // get model
        $model =  $this->find($id);

        if( ! $model) {
            throw new Exception("Couldnt find group with id {$id}");
        }

        if( ! $model->updateSingleParam($data)) {
            throw new Exception("Something went wrong");
        }

        return $model;
    }


    /**
     * Create field data and assign to resource
     *
     * @param Resource $resource
     * @param array $data
     * @return array || false when there are no errors
     */
    public function createAndAssignToResource(\Modulesgarden\Crm\Models\Resource &$resource, array $data = array())
    {
        // all fields that are active!
        $fields = Field::activeFields()->withValidators()->get();

        $fieldsData = array();
        $errors     = array();

        foreach ($fields as $field)
        {
            try{
                $fieldsData[] = $field->createNewData($resource, array_get($data, $field->id, array()));
            } catch (Exception $e) {
                $errors[] = $e->getMessage();
            }
        }

        return (!empty($errors) ? $errors : false);
    }


    /**
     * Handle assign validator to field
     *
     * @param type $id
     * @param type $data
     * @return FieldValidatorConfig
     */
    public function addFieldValidator($id, $data)
    {
        $parsed = array(
            'field_id' => $id,
            'type'     => array_get($data, 'type', null),
            'value'    => array_get($data, 'value', null),
            'error'    => array_get($data, 'error', null),
        );

        $new = new FieldValidatorConfig();
        $new->create($parsed);

        return $new;
    }
}
