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

use Modulesgarden\Crm\Models\Validators\Common;

use \Exception;

/**
 * Repository pattern for Records Statuses
 * Wrap certain actions for collection of our model or perform more complexed actions on model
 */
class FieldStatuses extends AbstractRepository implements RepositoryInterface
{
    /**
     * Determinate model used by this Repository
     *
     * @return \Modulesgarden\Crm\Models\FieldStatus
     */
    function determinateModel() {
        return 'Modulesgarden\Crm\Models\FieldStatus';
    }


    /**
     * Perform update for many elements
     *
     * @param bolean
     */
    public function massUpdate(array $list)
    {

        $statuses = $this->model->all();

        foreach ($statuses as $status)
        {
            if(array_get($list, $status->id.'.id') == $status->id)
            {
                $fill = array_get($list, $status->id);
                array_forget($fill, 'id');

                $status->fill($fill);
                $status->save();
            }
        }

        return true;
    }


    /**
     * Perform update for many elements
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getActive()
    {
        return $this->model->where('active', '=', 1)->orderBy('order', 'asc')->get();
    }


    /**
     * Return eloquent coll
     *
     * @deprecated since version 2.0.0 beta3, now generateSummaryWithCounters will be used
     * @return array
     */
    public function getWithCounter($adminID)
    {
        $return = $this->getModel()->orderBy('order', 'ASC')->get();

        foreach ($return as &$r) {
            $r->resourcesCount = $r->countRecordsForAdmin($adminID);
        }

        return $return->toArray();
    }

    /**
     * Retriev counters for certain admin & status
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function generateSummaryWithCounters(array $data = array())
    {
        $return = $this->getModel()->orderBy('order', 'ASC')->get();

        // maintain for requested Admin
        $requestedAdmin = array_get($data, 'admin_id', null);
        $requestedAdmin = (Common::isNumber($requestedAdmin)) ? $requestedAdmin : null;

        // lead or potential or both ?
        if(array_get($data, 'type_id', false) !== false) {
            $type_id = array_get($data, 'type_id', false);
        } else {
            $type_id = 0;
        }

        // maintain for specific campaign
        $requestedCampaign = array_get($data, 'campaign', null);
        $requestedCampaign = (Common::isNumber($requestedCampaign)) ? $requestedCampaign : null;

        foreach ($return as &$r) {
            $r->resourcesCount = $r->countRecordsFor($type_id, $requestedAdmin, $requestedCampaign);
        }

        return $return->toArray();
    }


    /**
     * Reorder all statuses in DB
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
}
