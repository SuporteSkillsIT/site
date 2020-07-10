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

use \Illuminate\Database\Capsule\Manager as DB;

use Modulesgarden\Crm\Repositories\Source\AbstractRepository;
use Modulesgarden\Crm\Repositories\Source\RepositoryInterface;

use Modulesgarden\Crm\Models\Resource;
use Modulesgarden\Crm\Models\ResourceType;
use Modulesgarden\Crm\Models\Validators\Common;

use \Exception;

/**
 * Just container for Resource Types
 * as repository pattern
 */
class ResourceTypes extends AbstractRepository implements RepositoryInterface
{
    /**
     * Determinate model used by this Repository
     *
     * @return \Modulesgarden\Crm\Models\ResourceType
     */
    function determinateModel() {
        return 'Modulesgarden\Crm\Models\ResourceType';
    }


    /**
     * Return all types in correct order
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getOrderred()
    {
        return $this->getModel()->orderBy('order', 'ASC')->get();
    }


    /**
     * Wrapper to handle udpate only certain parameters for model
     * usefull when we dont want to push every single parameter
     * Find model, and trigger correct method
     *
     * @param type $id
     * @param type $data
     * @return \Modulesgarden\Crm\Models\ResourceType
     * @throws Exception
     */
    public function updateSingleParamInModel($id, $data)
    {
        // get model
        $model =  $this->getModel()->find($id);

        if( ! $model) {
            throw new Exception("Couldnt find contact type with id {$id}");
        }

        if( ! $model->updateSingleParam($data)) {
            throw new Exception("Something went wrong");
        }

        return $model;
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
        
        $groups = $this->model->all();

        foreach ($groups as $group)
        {
            $group->order = array_get($newOrder, $group->id, 0);
            $group->save();
        }

        return true;
    }


    /**
     * Filter for navigation generate
     *
     * @return array
     */
    public static function getForNavigation()
    {
        // set up format
        $return = array(
            'navigation' => array(),
            'submenu'    => array(),
            'routing'    => array(),
        );

        $list = ResourceType::orderred()->withTrashed()->get();

        foreach ($list as $type)
        {
            if($type->active && $type->trashed() === false)
            {
                if($type->isVisibleInNavbar()) {
                    $return['navigation'][] = $type->toNavigationArray();
                }
                if($type->isVisibleInNavbarSubmenu()) {
                    $return['submenu'][] = $type->toNavigationArray();
                }
            }

            // in angular app we want to have all even trashed in memory so we could generate links/breadcrumbs for it
            $return['routing'][] = $type->toRoutingArray();
        }

        return $return;
    }


    public function convertOrDelete($data)
    {
        $model = ResourceType::find(array_get($data, 'id', false));

        if(is_null($model)) {
            throw new Exception('Invalid Contact Type');
        }

        if(array_get($data, 'archive', false) === true) {
            DB::table('crm_resources')
                ->where("type_id", '=', $model->id)
                ->update(array("deleted_at" => DB::raw('NOW()')));
        }

        $convert = ResourceType::find(array_get($data, 'convert', false));

        if( $convert && $convert->id ) {
            DB::table('crm_resources')
                ->where("type_id", '=', $model->id)
                ->update(array("type_id" => $convert->id));
        }

        return $model->delete();
    }
}
