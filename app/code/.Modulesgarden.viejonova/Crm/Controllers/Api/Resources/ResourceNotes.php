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


namespace Modulesgarden\Crm\Controllers\Api\Resources;

use Modulesgarden\Crm\Controllers\Source\AbstractController;
use Modulesgarden\Crm\Repositories\Notes;
use Modulesgarden\Crm\Models\Note;
use Modulesgarden\Crm\Models\Magento\Admin;
use Modulesgarden\Crm\Models\Validators\Common;
use \Exception;

use \Illuminate\Database\Capsule\Manager as DB;

/**
 * Class to maintain actions for maintain notes
 */
class ResourceNotes extends AbstractController
{
    /**
     * if there is set limit for queries
     * 
     * @var integer
     */
    protected $queryLimit = null;

    /**
     * constructor
     * set up repository model
     */
    public function __construct()
    {
        parent::__construct();

        // set repository
        $this->repository = new Notes();
    }


    /**
     * Get notes only not deleted one
     */
    public function get($id)
    {
        return $this->getList($id, false);
    }

    /**
     * Get notes only not deleted one
     */
    public function getLimited($id, $limit)
    {
        if(Common::isPositiveNumber($limit)) {
            $this->queryLimit = intval($limit);
        }
        return $this->getList($id, false);
    }

    /**
     * Get notes with deleted one
     */
    public function getWithDeleted($id)
    {
        return $this->getList($id, true);
    }

    /**
     * Get notes with deleted one
     */
    public function getWithDeletedLimited($id, $limit)
    {
        if(Common::isPositiveNumber($limit)) {
            $this->queryLimit = intval($limit);
        }

        return $this->getList($id, true);
    }

    /**
     * Get field groups in correct order
     */
    protected function getList($id, $withTrashed = false)
    {
        try {
            $result = $this->repository->getModel()
                ->where('resource_id', '=', $id)
//                ->with(array('admin' => function($query){
//                    $query->select('id', 'firstname', 'lastname', 'email');
//                }))
                ->joinAdminAvatar()
                ->orderBy('updated_at', 'DESC')
                ->orderBy('created_at', 'DESC');

            if($withTrashed) {
                $result = $result->withTrashed();
            }

            if(!is_null($this->queryLimit)) {
                $result = $result->take($this->queryLimit);
            }

            $resultArray = $result->get()->toArray();

        } catch (Exception $ex) {

            // some logging errors mechanism

            $this->app->response->setStatus(409);
            return $this->returnData(array(
                'status' => 'error',
                'msg'    => $ex->getMessage(),
            ));
        }


        return $this->returnData($resultArray);
    }

    
    /**
     * Add new note
     *
     * @param type $id resource to which one you want to add note (from route)
     * @return type
     * @throws Exception
     */
    public function addNote($id)
    {
        try {
            $requestData = json_decode($this->app->request->getBody(), true);
            if(empty($requestData)) $requestData = array();

            $new = $this->repository->addNoteForResource($id, $requestData);

            if( $new->id ) {
                return $this->returnData(array(
                    'status' => 'success',
                    'msg'    => 'New Note has been created',
                    'new'    => $new->toArray(),
                ));
            } else {
                throw new Exception('Something went wrong.');
            }

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
     * We use soft delete system to "hide" notes
     *
     * @param type $id      resource id
     * @param type $noteID  particular note id
     * @return type
     */
    public function softDelete($id, $noteID)
    {

        try {
            $note = $this->repository->softDeleteFor($id, $noteID);

            return $this->returnData(array(
                'status'        => 'success',
                'msg'           => 'Note has been hidden',
                'updated_at'    => $note->updated_at->toDateTimeString(),
                'deleted_at'    => $note->deleted_at->toDateTimeString(),
            ));

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
     * We use force delete to permanently delete note
     *
     * @param type $id      resource id
     * @param type $noteID  particular note id
     * @return type
     */
    public function forceDelete($id, $noteID)
    {

        try {
            $note = $this->repository->forceDeleteFor($id, $noteID);

            return $this->returnData(array(
                'status' => 'success',
                'msg'    => 'Note has been deleted',
            ));

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
     * We use force delete to permanently delete note
     *
     * @param type $id      resource id
     * @param type $noteID  particular note id
     * @return type
     */
    public function restoreDeleted($id, $noteID)
    {

        try {
            $note = $this->repository->restoreNote($id, $noteID);

            return $this->returnData(array(
                'status'        => 'success',
                'msg'           => 'Note has been restored',
                'updated_at'    => $note->updated_at->toDateTimeString(),
                'deleted_at'    => null,
            ));

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
     * Handle Edit note
     *
     * @param type $id      resource id
     * @param type $noteID  particular note id
     * @return type
     */
    public function editNote($id, $noteID)
    {

        try {

            $requestData = json_decode($this->app->request->getBody(), true);
            
            if(empty($requestData)) {
                $requestData = array();
            }

            $note = $this->repository->handleEditContent($id, $noteID, $requestData);

            return $this->returnData(array(
                'status'        => 'success',
                'msg'           => 'Note has been restored',
                'note'          => $note->toArray(),
            ));

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
