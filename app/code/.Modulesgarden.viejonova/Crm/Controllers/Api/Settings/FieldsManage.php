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

use Modulesgarden\Crm\Repositories\Fields;
use Modulesgarden\Crm\Models\FieldValidatorConfig;
use Modulesgarden\Crm\Models\FieldOption;
use \Exception;

use \Illuminate\Database\Capsule\Manager as DB;

/**
 * Class to maintain actions for single lead instance
 */
class FieldsManage extends AbstractController
{
    /**
     * constructor
     * set up repository model
     */
    public function __construct()
    {
        parent::__construct();

        // set repository
        $this->repository = new Fields();
    }


    /**
     * Get field groups in correct order
     *
     * @return array
     */
    public function query()
    {
        try {

            $groups = $this->repository->getModel()->orderBy('order', 'ASC')->with('group')->get();

        } catch (Exception $ex) {

            // some logging errors mechanism

            $this->app->response->setStatus(409);
            return $this->returnData(array(
                'status' => 'error',
                'msg'    => $ex->getMessage(),
            ));
        }


        return $this->returnData($groups->toArray());
    }

    /**
     * add new fields group
     *
     * @return array
     */
    public function addField()
    {
        $json = $this->app->request->getBody();
        $data = json_decode($json, true);

        try {
            $new = $this->repository->create($data);

            if( $new ) {
                $return = array(
                    'status' => 'success',
                    'msg'    => 'New Field has been created',
                    'new'    => $new->toArray(),
                );
            } else {
                throw new Exception('Something went wrong.');
            }


        } catch (\Exception $ex) {

            // some logging errors mechanism
            $this->app->response->setStatus(409);
            $return = array(
                'status' => 'error',
                'msg'    => $ex->getMessage(),
            );
        }


        return $this->returnData($return);
    }


    /**
     * Update single atribute to field
     *
     * @param type $id
     * @return type
     */
    public function updateField($id)
    {
        // get from request
        $data  = json_decode($this->app->request->getBody(), true);

        try {
            // perform uprate
            $result  =  $this->repository->updateSingleParamInModel($id, $data);

            $return = array(
                'status' => 'success',
                'msg'    => 'Field has been updated',
                'new'    => $result->toArray(),
            );
        } catch (\Exception $ex) {

            // some logging errors mechanism

            $this->app->response->setStatus(409);
            $return = array(
                'status' => 'error',
                'msg'    => $ex->getMessage(),
            );
        }

        return $this->returnData($return);


    }


    /**
     * Update single atribute to field
     *
     * @param type $id
     * @return type
     */
    public function deleteField($id)
    {
        try {
            $result  =  $this->repository->delete($id);

            if( ! $result) {
                throw new Exception('Something Went Wrong. Could not deleted Role.');
            }

            $return = array(
                'status' => 'success',
                'msg'    => 'Field has been deleted',
            );
        } catch (Exception $ex) {

            // some logging errors mechanism
            $this->app->response->setStatus(409);
            $return = array(
                'status' => 'error',
                'msg'    => $ex->getMessage(),
            );
        }

        return $this->returnData($return);


    }

    /**
     * Reorder all groups
     *
     * @return array
     */
    public function reorder()
    {
        try {

            $json = $this->app->request->getBody();
            $data = json_decode($json, true);

            $newOrder = array_get($data, 'order', array());

            if( $this->repository->reorder($newOrder) ) {
                $return = array(
                    'status' => 'success',
                    'msg'    => 'Order Has has been updated',
                );
            } else {
                throw new Exception('Something went wrong.');
            }


        } catch (Exception $e) {

            // some logging errors mechanism

            $this->app->response->setStatus(404);
            $return = array(
                'status' => 'error',
                'msg'    => $e->getMessage(),
            );
        }


        return $this->returnData($return);
    }



    /**
     * Obtain and return single field
     *
     * @param type $id
     * @return type
     */
    public function getField($id)
    {
        try {
            // perform uprate
            $field  =  $this->repository->find($id);

            if( ! $field) {
                throw new Exception("Couldnt find field with id {$id}");
            }

            $return = $field->toArray();

        } catch (\Exception $ex) {

            // some logging errors mechanism
            $this->app->response->setStatus(409);
            $return = array(
                'status' => 'error',
                'msg'    => $ex->getMessage(),
            );
        }

        return $this->returnData($return);


    }


    /**
     * New validator configuration
     * that is assigned to field
     *
     * @param type $id
     * @return type
     * @throws Exception
     */
    public function addValidator($id)
    {

        try {

            $json = $this->app->request->getBody();
            $data = json_decode($json, true);

            $new = $this->repository->addFieldValidator($id, $data);

            if( $new ) {
                $return = array(
                    'status' => 'success',
                    'msg'    => 'New Validator has been assigned',
                    'new'    => $new->toArray(),
                );
            } else {
                throw new Exception('Something went wrong.');
            }

        } catch (\Exception $ex) {

            // some logging errors mechanism
            $this->app->response->setStatus(409);
            $return = array(
                'status' => 'error',
                'msg'    => $ex->getMessage(),
            );
        }

        return $this->returnData($return);
    }


    /**
     * Plain return many validators
     * for single field
     *
     * @param type $id
     * @return type
     */
    public function getValidatorFor($id)
    {

        try {

            $validators = FieldValidatorConfig::where('field_id', '=', $id)->get();

        } catch (\Exception $ex) {

            $this->app->response->setStatus(409);
            return $this->returnData(array(
                'status' => 'error',
                'msg'    => $ex->getMessage(),
            ));
        }

        return $this->returnData($validators->toArray());
    }


    /**
     * Get rid of validator
     *
     * @param type $id
     * @param type $validatorID
     * @return type
     */
    public function deleteValidator($id, $validatorID)
    {

        try {

            $validator = FieldValidatorConfig::where('field_id', '=', $id)->where('id', '=', $validatorID)->first();
            $validator->delete();

            $return = array(
                'status' => 'success',
                'msg'    => 'Validator Assigned to Field has been deleted',
            );
        } catch (Exception $ex) {

            // some logging errors mechanism
            $this->app->response->setStatus(409);
            $return = array(
                'status' => 'error',
                'msg'    => $ex->getMessage(),
            );
        }

        return $this->returnData($return);
    }


    // field options simple  mmanage


    /**
     * Add option to choose for field
     *
     * @param type $id
     * @return type
     * @throws Exception
     */
    public function addFieldOption($id)
    {

        try {

            $json = $this->app->request->getBody();
            $data = json_decode($json, true);

            $new = new FieldOption();
            $new->create($data);

            if( $new ) {
                $return = array(
                    'status' => 'success',
                    'msg'    => 'New Option has been added',
                );
            } else {
                throw new Exception('Something went wrong.');
            }

        } catch (\Exception $ex) {

            // some logging errors mechanism
            $this->app->response->setStatus(409);
            $return = array(
                'status' => 'error',
                'msg'    => $ex->getMessage(),
            );
        }

        return $this->returnData($return);
    }

    /**
     * get all field options for single specified field
     *
     * @param type $id
     * @return type
     */
    public function getFieldOption($id)
    {

        try {

            $list = FieldOption::where('field_id', '=', $id)->get();

        } catch (\Exception $ex) {

            $this->app->response->setStatus(409);
            return $this->returnData(array(
                'status' => 'error',
                'msg'    => $ex->getMessage(),
            ));
        }

        return $this->returnData($list->toArray());
    }



    /**
     * Delete field option
     *
     * @param type $id
     * @param type $optionsID
     * @return type
     */
    public function deleteFieldOption($id, $optionsID)
    {

        try {

            $option = FieldOption::where('field_id', '=', $id)->where('id', '=', $optionsID)->first();
            $option->delete();

            $return = array(
                'status' => 'success',
                'msg'    => 'Option has been deleted',
            );
        } catch (Exception $ex) {

            // some logging errors mechanism
            $this->app->response->setStatus(409);
            $return = array(
                'status' => 'error',
                'msg'    => $ex->getMessage(),
            );
        }

        return $this->returnData($return);
    }


    /**
     * Basically update field visible name
     * Since each option is assgigned by ID this can be easly done to many records in this way
     *
     * @param type $id
     * @param type $optionsID
     * @return type
     */
    public function updateOption($id, $optionsID)
    {

        try {

            $json = $this->app->request->getBody();
            $data = json_decode($json, true);

            $option = FieldOption::where('field_id', '=', $id)->where('id', '=', $optionsID)->firstOrFail();
            $option->updateValue(array_get($data, 'value', false));

            return $this->returnData($option->toArray());

        } catch (Exception $ex) {

            // some logging errors mechanism
            $this->app->response->setStatus(409);
            $return = array(
                'status' => 'error',
                'msg'    => $ex->getMessage(),
            );
        }

        return $this->returnData($return);
    }


    /**
     * heheszki to bedzie dobre
     *
     * @return array
     */
    public function getWithOptionsValidators()
    {
        try {

            $groups = $this->repository->getModel()->orderBy('order', 'ASC')->with('group')->get();

        } catch (Exception $ex) {

            // some logging errors mechanism

            $this->app->response->setStatus(409);
            return $this->returnData(array(
                'status' => 'error',
                'msg'    => $ex->getMessage(),
            ));
        }


        return $this->returnData($groups->toArray());
    }
}
