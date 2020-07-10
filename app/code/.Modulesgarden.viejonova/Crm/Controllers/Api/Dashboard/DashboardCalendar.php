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
use Modulesgarden\Crm\Repositories\Followups;
use \Exception;


class DashboardCalendar extends AbstractController
{

    protected $adminID  = false;
    protected $year     = false;
    protected $month    = false;

    protected $requestData = array();

    public function __construct()
    {
        parent::__construct();

        $this->requestData  = json_decode($this->app->request->getBody(), true);
        $this->adminID      = array_get($this->requestData, 'admin', false);
        $this->year         = array_get($this->requestData, 'year', false);
        $this->month        = array_get($this->requestData, 'month', false);
        $this->campaign     = array_get($this->requestData, 'campaign', false);
    }


    public function getCounters()
    {

        try {

            $repo = new Followups();

            return $this->returnData($repo->getCountersForCalendar($this->year, $this->month+1, $this->adminID, $this->campaign));


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
