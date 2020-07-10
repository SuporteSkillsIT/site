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


namespace Modulesgarden\Crm\Controllers\Api\Followups;

use Modulesgarden\Crm\Controllers\Source\AbstractController;
use Modulesgarden\Crm\Models\Followup;
use Modulesgarden\Crm\Repositories\Followups;
use Modulesgarden\Crm\Repositories\Reminders;


class ListCalendar extends AbstractController
{
    /**
     * constructor
     * set up repository model
     */
    public function __construct()
    {
        parent::__construct();

        // set repository
        $this->repository = new Followups();
    }



    /**
     * Return all followups assigned to currently logged in admin
     *
     * @return type
     */
    public function getMine()
    {
        try {

            $this->returnData($this->repository->queryCalendarForAdmin($this->app->currentAdmin->user_id)->toArray());

        } catch (Exception $ex) {

            // some logging errors mechanism

            $this->app->response->setStatus(409);
            return $this->returnData(array(
                'status' => 'error',
                'msg'    => $ex->getMessage(),
            ));
        }
    }


    /**
     * Return all followups assigned to selected admin
     *
     * @return type
     */
    public function getForAdmin($id)
    {
        try {

            $this->returnData($this->repository->queryCalendarForAdmin($id)->toArray());

        } catch (Exception $ex) {

            // some logging errors mechanism

            $this->app->response->setStatus(409);
            return $this->returnData(array(
                'status' => 'error',
                'msg'    => $ex->getMessage(),
            ));
        }
    }

    /**
     * Return all followups assigned to selected admin
     *
     * @return type
     */
    public function getForAllAdmins()
    {
        try {

            $this->returnData($this->repository->queryCalendarForAllAdmins()->toArray());

        } catch (Exception $ex) {

            // some logging errors mechanism

            $this->app->response->setStatus(409);
            return $this->returnData(array(
                'status' => 'error',
                'msg'    => $ex->getMessage(),
            ));
        }
    }


    /**
     * Return all followups assigned to currently logged in admin
     *
     * @return type
     */
    public function getFollowupReminders($id)
    {
        try {

            $remindersRepo = new Reminders();
            $this->returnData($remindersRepo->getFollowupReminders($id)->toArray());

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
