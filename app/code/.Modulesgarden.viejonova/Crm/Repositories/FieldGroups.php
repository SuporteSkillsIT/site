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

use Modulesgarden\Crm\Models\FieldGroup;

/**
 * Just container for Orders
 * as repository pattern
 */
class FieldGroups extends AbstractRepository implements RepositoryInterface
{
    /**
     * Determinate model used by this Repository
     *
     * @return \Modulesgarden\Crm\Models\FieldGroup
     */
    function determinateModel() {
        return 'Modulesgarden\Crm\Models\FieldGroup';
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
        if( empty($newOrder) )
        {
            $this->app->response->setStatus(404);
            throw new Exception('Wrong order parameters provided');
        }
        
        $groups = $this->model->all();

        foreach ($groups as $group)
        {
            $group->order = array_get($newOrder, $group->id, 0);
            $group->save();
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
     * @return \Modulesgarden\Crm\Models\FieldGroup
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
     * Return groups that contains fields assigned to them
     * also options for fields
     * and also validators
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function withFieldsAndValidators()
    {
        return $this->model->where('active', '=', 1)->with(array(
            // inje
            'fields' => function($query) {
                $query->where('active', '=', 1)->orderBy('order', 'asc');
            },
            'fields.validators',
            'fields.options',
            )
        )->orderBy('order', 'asc')->get();
    }


    /**
     * Bring me All acrive fields, and joined to each field date for certain resource
     *
     * @param type $resourceID
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllForResourceSummary($resourceID)
    {
        // ok this is nice (looking) approach
        // optimalization for this is sh**y
        // we are going to obtain it in other way, then order manually
        return FieldGroup::activeGroups()
                ->activeGroups()
                ->joinActiveFields()
                ->joinValidators()
                ->joinOptions()
                ->joinFieldsDataFor($resourceID)
                ->orderred()
                ->get();
    }
}
