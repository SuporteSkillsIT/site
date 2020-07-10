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

use Modulesgarden\Crm\Models\FollowupType;

/**
 * Just container for FollowupTypes
 * as repository pattern
 */
class FollowupTypes extends AbstractRepository implements RepositoryInterface
{
    /**
     * Determinate model used by this Repository
     *
     * @return FollowupType
     */
    function determinateModel() {
        return 'Modulesgarden\Crm\Models\FollowupType';
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
     * @return FollowupType
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
     * Just scope Types collection by order
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getOrderred()
    {
        return $this->getModel()->orderred()->get();
    }


    /**
     * Drop Followup Type from DB
     *
     * @param type $id
     * @return bolean
     */
    public function deleteType($id)
    {
        $f = FollowupType::findOrFail($id);

        return $f->delete();
    }
}
