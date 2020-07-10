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

use Modulesgarden\Crm\Repositories\FieldGroups;

use Modulesgarden\Crm\Models\Field;
use Modulesgarden\Crm\Models\FieldData;
use Modulesgarden\Crm\Models\Resource;
use Modulesgarden\Crm\Models\Log;

use Modulesgarden\Crm\Integration\Slim\SlimApp;
use Modulesgarden\Crm\Services\Language;
use Carbon\Carbon;
use \Exception;

/**
 * Just container for Field Datas
 * Nothing much here except trigger scopes function to obtain various records
 * as repository pattern
 */
class FieldDatas extends AbstractRepository implements RepositoryInterface
{
    /**
     * Determinate model used by this Repository
     *
     * @return \Modulesgarden\Crm\Models\FieldData
     */
    function determinateModel() {
        return 'Modulesgarden\Crm\Models\FieldData';
    }


    /**
     * Obtain everything for our resource
     *
     * @param type $resourceID
     * @return array
     */
    public function getAllForResourceSummary($resourceID)
    {
        $repository = new FieldGroups();
        $fields     = $repository->withFieldsAndValidators();

        return array(
            'groups' => $fields->toArray(),
            'fields' => $this->getArrayFieldsForResourceSummary($resourceID),
        );
    }


    /**
     * Get assigned fields to ressource as array, parsed with full mnap dependiences
     *
     * @param type $resourceID
     * @return array
     */
    public function getArrayFieldsForResourceSummary($resourceID)
    {
        // base query with limits etc to obrain what we need
        $query = $this->getModel()->joinField()->withTrashed()->withResource($resourceID)->joinAssignedOptionsAndOption()->get();

        return $query->toArray();
    }


    /**
     * Get assigned fields to ressource
     *
     * @param type $resourceID
     * @return array
     */
    public function getOnlyFieldsForResourceSummary($resourceID)
    {
        // base query with limits etc to obrain what we need
        $query = $this->getModel()->joinField()->withTrashed()->withResource($resourceID)->joinAssignedOptionsAndOption()->get();

        return self::toArrayForSmarty($query);
    }



    public static function toArrayForSmarty($data)
    {
        $return = array();

        foreach ($data as $fieldData)
        {
            $tmp = array(
                'id'            => $fieldData->field->id,
                'type'          => $fieldData->field->type,
                'active'        => $fieldData->field->active,
                'name'          => $fieldData->field->name,
                'description'   => $fieldData->field->description,
                // for text/textarea
                'data'          => $fieldData->data,
                'options'       => null,
            );

            if($fieldData->options) {
                $tmp['options'] = $fieldData->options->map(function($item, $key) {
                    return $item->option->value;
                })->toArray();
            }



            $return[$fieldData->field_id] = $tmp;
        }

        return $return;
    }

    public static function toArrayForExport($data)
    {
        $return = array();

        foreach ($data as $fieldData)
        {
            if($fieldData->options->count()) {
                $tmpOptions = $fieldData->options->map(function($item, $key) {
                    return $item->option->value;
                })->toArray();

                $tmp = implode(';', $tmpOptions);
            } else {
                $tmp = (is_null($fieldData->data) ? '' : $fieldData->data);
            }

            $return[$fieldData->field_id] = $tmp;
        }

        return $return;
    }

    /**
     * Trigger set field data, create if not exist in db
     *
     * @param type $resourceID
     * @param array $data
     * @return boolean
     * @throws Exception
     */
    public function updateData($resourceID, array $data = array())
    {
        $resourceID  = intval($resourceID);
        $fieldID     = array_get($data, 'field_id');
        $fieldDataID = array_get($data, 'field_data_id');

        $field       = Field::findOrFail($fieldID);
        $resource    = Resource::findOrFail($resourceID);
        $fieldData   = $field->getFieldDataOrNew($fieldDataID, $resourceID);

        $newVal = array_get($data, 'data', false);

        // damm, xeditable sent us empty, so initially when there is no assigned date to field
        // it will not send us any parameter (fuc***it)
        // so that condition wont mach, and error will be displayed
//        if( $newVal !== false )
//        {
            // run
            if( $fieldData->setData($newVal) )
            {
                // logs
                $message = Language::translate("log.field.{$fieldData->field_type}.update", array(
                    'id'        => $field->id,
                    'field'     => $field->name,
                    'value'     => $fieldData->getData(),
                ));

                $log = new Log(array(
                    'resource_id'   => $resourceID,
                    'admin_id'      => SlimApp::getInstance()->currentAdmin->user_id,
                    'event'         => 'Field Update',
                    'date'          => Carbon::now(),
                    'message'       => $message,
                ));

                $log->save();

                // update dates for this resource
                $resource->touch();

                // we want to obtain objects set, to update frontend base
                $fieldDataArray = $fieldData->load('options')->toArray();
                unset($fieldDataArray['field']);

                return array(
                    'status'        => 'success',
                    'msg'           => 'Field has been updated',
                    'updated_at'    => $resource->updated_at->toDateTimeString(),
                    'data'          => $fieldDataArray,
                );

            } else {
                throw new Exception('something went wrong in update');
            }
//        } else {
//            throw new Exception('Did not recieved value to update');
//        }

        return false;
    }

}
