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


namespace Modulesgarden\Crm\Controllers\Api\Settings;

use Modulesgarden\Crm\Controllers\Source\AbstractController;
use Modulesgarden\Crm\Models\Field;
use Modulesgarden\Crm\Models\Resource;
use Modulesgarden\Crm\Helpers\TablesFieldViews;
use \Exception;

/**
 * Class to maintain actions for single lead instance
 */
class FieldView extends AbstractController
{
    /**
     * All Possible columns to render
     *
     * @return array
     */
    public function allColumns()
    {
        try {

            $available = TablesFieldViews::allColumns();


        } catch (Exception $ex) {

            // some logging errors mechanism

            $this->app->response->setStatus(409);
            return $this->returnData(array(
                'status' => 'error',
                'msg'    => $ex->getMessage(),
            ));
        }


        return $this->returnData($available);
    }


    /**
     * All Possible columns to render
     *
     * @return array
     */
    public function getForAdmin()
    {
        try {

            $available = TablesFieldViews::allForAdmin();


        } catch (Exception $ex) {

            // some logging errors mechanism

            $this->app->response->setStatus(409);
            return $this->returnData(array(
                'status' => 'error',
                'msg'    => $ex->getMessage(),
            ));
        }


        return $this->returnData($available);
    }


    /**
     * All Configuration for requested table route
     *
     * @return array
     */
    public function getTopeForAdmin($type)
    {
        try {

            $available = TablesFieldViews::getSingleForAdmin($type);

        } catch (Exception $ex) {

            // some logging errors mechanism

            $this->app->response->setStatus(409);
            return $this->returnData(array(
                'status' => 'error',
                'msg'    => $ex->getMessage(),
            ));
        }


        return $this->returnData($available);
    }



    /**
     * Update Admin Field Views
     *
     * @return array
     */
    public function updateForAdmin()
    {
        try {


            $json = $this->app->request->getBody();
            $data = json_decode($json, true);

            $visible = array_get($data, 'data', array());
            $rule    = array_get($data, 'rule', null);

            $updated = TablesFieldViews::updateForAdmin($rule, $visible);

            return $this->returnData(array(
                'status' => 'success',
                'msg'    => 'Order Has been updated',
            ));


        } catch (Exception $ex) {

            // some logging errors mechanism

            $this->app->response->setStatus(409);
            return $this->returnData(array(
                'status' => 'error',
                'msg'    => $ex->getMessage(),
            ));
        }


        return $this->returnData($available);
    }
}
