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


use Modulesgarden\Crm\Integration\Slim\SlimApp;

use Modulesgarden\Crm\Models\File;

use Modulesgarden\Crm\Repositories\Source\AbstractRepository;
use Modulesgarden\Crm\Repositories\Source\RepositoryInterface;

use Modulesgarden\Crm\Helpers\ManageFiles;

use \Exception;

/**
 * Just container for Files
 * as repository pattern
 */
class Files extends AbstractRepository implements RepositoryInterface
{
    /**
     * Determinate model used by this Repository
     *
     * @return File
     */
    function determinateModel() {
        return 'Modulesgarden\Crm\Models\File';
    }


    /**
     * Basically create new note
     *
     * @param type $resourceId
     * @param type $noteData
     * @return File
     */
    public function addFileForResource($resourceId, $file, $gotData)
    {
        $movedFile = ManageFiles::moveUploadedFileForResource($resourceId, $file);

        $data = array();
        $data = array_add($data, 'resource_id',     intval($resourceId));
        $data = array_add($data, 'admin_id',        SlimApp::getInstance()->currentAdmin->user_id);
        $data = array_add($data, 'file_name',       array_get($movedFile, 'oryginal', ''));
        $data = array_add($data, 'path_name',       array_get($movedFile, 'name', ''));
        $data = array_add($data, 'description',     array_get($gotData, 'description', ''));

        $new = $this->model->create($data);
        $new->save();

        return $new;
    }


    /**
     *
     * @param type $resourceID
     * @param array $data
     * @return type
     */
    public function parseForTable($resourceID, array $data = array())
    {

        // limit
        $limit      = array_get($data, 'params.pagination.number', 10);
        $ofset      = array_get($data, 'params.pagination.start', 0);
        // order
        $orderBy    = array_get($data, 'params.sort.predicate', 'updated_at');
        $orderDesc  = array_get($data, 'params.sort.reverse', true);
        $orderDesc  = ($orderDesc === true) ? 'DESC' : 'ASC';

        // search
        $description        = array_get($data, 'params.search.predicateObject.description', false);
        $admin_id           = array_get($data, 'params.search.predicateObject.admin_id', false);

        if($orderBy == 'admin') {
            $orderBy = 'admin_id';
        } elseif($orderBy == 'type') {
            $orderBy = 'type_id';
        }

        // prepare base query with no conditions for count all elements
        $queryTotal = File::whereResource($resourceID);
        // base query with limits etc to obrain what we need
        $query = $this->getModel()
                      ->whereResource($resourceID)
                      ->joinAdminRelevant()
                      ->orderBy($orderBy, $orderDesc)
                      ->take($limit)
                      ->offset($ofset);



        // trigger search
        if(!empty($description) && $description !== false) {
            $query      = $query->withDescriptionOrName($description);
            $queryTotal = $queryTotal->withDescriptionOrName($description);
        }
        // trigger $typeId
        if(!empty($admin_id) && $admin_id !== false) {
            $query      = $query->where('admin_id', '=', $admin_id);
            $queryTotal = $queryTotal->where('admin_id', '=', $admin_id);
        }

        // run this damm queries
        $results = $query->get();
        $count   = $queryTotal->count();

        $parsed = array();
        foreach ($results as $r)
        {
            if( ! $r->verifyFile()) {
                continue;
            }
            $tmp = $r->toArray();
            $tmp = array_add($tmp, 'file_size', $r->fileSize);

            $parsed[] = $tmp;
        }

        // gather to data format for smart table
        $return = array(
            'data'  => $parsed,
            'total' => $count,
        );

        return $return;
    }


    /**
     * Render file to browser
     *
     * @param type $id
     * @param type $fileId
     * @throws Exception
     */
    public function getFile($id, $fileId)
    {
        $file = $this->getModel()->find($fileId);

        if(is_null($file)) {
            throw new Exception('File not found');
        }

        if(file_exists($file->fileFullPath))
        {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="'.$file->file_name.'"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file->fileFullPath));
            readfile($file->fileFullPath);

            exit;
        } else {
            throw new Exception('File not found');
        }
    }


    /**
     * Perform delete File operation
     *
     * @param type $resourceId
     * @param type $fileID
     * @return bolean
     * @throws Exception
     */
    public function deleteFile($resourceId, $fileID)
    {
        $file = $this->getModel()->find($fileID);

        if(is_null($file)) {
            throw new Exception(sprintf("Couldn't find File #%s for Resource %s", $fileID, $resourceId));
        }

        return $file->dropFile();
    }
}
