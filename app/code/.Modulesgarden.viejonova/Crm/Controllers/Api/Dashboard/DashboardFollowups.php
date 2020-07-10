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


namespace Modulesgarden\Crm\Controllers\Api\Dashboard;

use Modulesgarden\Crm\Controllers\Source\AbstractController;

use Modulesgarden\Crm\Repositories\Logs;
use Modulesgarden\Crm\Repositories\Followups;
use Modulesgarden\Crm\Models\Magento\Admin;
use \Exception;

use \Illuminate\Database\Capsule\Manager as DB;

/**
 * Class to maintain actions for single lead instance
 */
class DashboardFollowups extends AbstractController
{
    protected $adminID  = false;
    protected $year     = false;
    protected $month    = false;
    protected $day      = false;

    protected $requestData = array();


    /**
     * constructor
     * set up repository model
     */
    public function __construct()
    {
        parent::__construct();

        // set repository
        $this->repository = new Followups();

        $this->requestData  = json_decode($this->app->request->getBody(), true);
        if(empty($this->requestData)) {
            $this->requestData = array();
        }
        $this->adminID      = array_get($this->requestData, 'admin', false);
        $this->year         = array_get($this->requestData, 'year', false);
        $this->month        = array_get($this->requestData, 'month', false);
        $this->day          = array_get($this->requestData, 'day', false);
    }


    /**
     * Parse followups for table for dashboard
     *
     * @return type
     */
    public function getForTable()
    {
        try {

            $requestData = json_decode($this->app->request->getBody(), true);
            if(empty($requestData)) {
                $requestData = array();
            }

            // if its from API, we require provided admin ID
            if($this->app->config('isWhmcsAPIcall') === true)
            {
                array_set($requestData, 'params.search.predicateObject.day', array_get($requestData, 'date'));
                $adminID = ( array_get($requestData, 'adminID') == null ? 0 : array_get($requestData, 'adminID'));
            } else {
                $adminID = $this->adminID;
            }


            $this->returnData($this->repository->parseForDashboardTable($requestData, $adminID, $this->app->config('isWhmcsAPIcall')));

        } catch (Exception $ex) {

            // some logging errors mechanism

            $this->app->response->setStatus(409);
            return $this->returnData(array(
                'status' => 'error',
                'msg'    => $ex->getMessage(),
            ));
        }
    }
}
