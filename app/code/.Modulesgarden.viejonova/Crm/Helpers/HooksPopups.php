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


namespace Modulesgarden\Crm\Helpers;


use \Illuminate\Database\Capsule\Manager as DB;
use \Modulesgarden\Crm\Integration\Slim\SlimApp;
use Modulesgarden\Crm\Models\Resource;
use Modulesgarden\Crm\Repositories\FieldDatas;
use \Exception;

/**
 * Class to maintain actions for single lead instance
 */
class HooksPopups
{
    public static function getSummaryForJqueryUI($id)
    {

        $resource = Resource::withTrashed()->with(array('admin', 'status', 'ticket', 'client'))
            ->with(array('status', 'ticket', 'client'))
            ->with(array('admin' => function($query){
                $query->select('id','roleid', 'username', 'firstname', 'lastname', 'email');
            }))
            ->find($id);

//        $resultArray = $resource->toArray();

        $fieldsRepo = new FieldDatas();
        $fields = $fieldsRepo->getAllForResourceSummary($id);

        return array_merge(array('resource' => $resource), $fields);
    }
}
