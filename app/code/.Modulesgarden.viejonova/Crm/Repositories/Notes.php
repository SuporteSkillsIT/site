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

use Modulesgarden\Crm\Services\Language;

use Modulesgarden\Crm\Repositories\Source\AbstractRepository;
use Modulesgarden\Crm\Repositories\Source\RepositoryInterface;

use Modulesgarden\Crm\Models\Note;
use Modulesgarden\Crm\Models\Log;

use \Exception;
use Carbon\Carbon;

/**
 * Just container for notes
 * as repository pattern
 */
class Notes extends AbstractRepository implements RepositoryInterface
{
    /**
     * Determinate model used by this Repository
     *
     * @return Note
     */
    function determinateModel() {
        return 'Modulesgarden\Crm\Models\Note';
    }


    /**
     * Basically create new note
     *
     * @param type $resourceId
     * @param type $noteData
     * @return Note
     */
    public function addNoteForResource($resourceId, $noteData)
    {
        $data = array();
        $data = array_add($data, 'content',     array_get($noteData, 'content', null));
        $data = array_add($data, 'resource_id', intval($resourceId));

        if(array_get($noteData, 'admin') != null) {
            $data = array_add($data, 'admin_id', array_get($noteData, 'admin'));
        } else {
            $data = array_add($data, 'admin_id', SlimApp::getInstance()->currentAdmin->user_id);
        }
        $new = $this->getModel()->fill($data);
        $new->save();
        $new->load(array('admin'=>function($query) {
            $query->joinAvatar();
        }));

        $log = new Log(array(
            'resource_id'   => $resourceId,
            'admin_id'      => array_get($data, 'admin_id'),
            'event'         => 'Note Add',
            'date'          => Carbon::now(),
            'message'       => Language::translate('log.note.new', array('conent' => array_get($noteData, 'content', null))),
        ));
        $log->save();

        return $new;
    }


    /**
     * Hide note
     * (use soft delete mechanism)
     *
     * @param type $resourceId
     * @param type $noteID
     * @return bolean
     */
    public function softDeleteFor($resourceId, $noteID)
    {
        return $this->deleteNote($resourceId, $noteID, false);
    }


    /**
     * Permanently delete note from DB
     *
     * @param type $resourceId
     * @param type $noteID
     * @return bolean
     */
    public function forceDeleteFor($resourceId, $noteID)
    {
        return $this->deleteNote($resourceId, $noteID, true);
    }


    /**
     * Perform delete operation
     * either it is soft or hard delete
     *
     * @param type $resourceId
     * @param type $noteID
     * @param type $force
     * @return type
     * @throws Exception
     */
    protected function deleteNote($resourceId, $noteID, $force = false)
    {
        $found = $this->getModel()->withTrashed()->where('resource_id', '=', $resourceId)->where('id', '=', $noteID)->first();

        if(is_null($found)) {
            throw new Exception(sprintf("Couldn't find Note #%s for Resource %s", $noteID, $resourceId));
        }

        if($force === true) {
            return $found->forceDelete();
        }

        if($found->delete()) {
            return $found;
        }
        

        throw new Exception(sprintf("Something went wrong at delete Note #%s for Resource %s", $noteID, $resourceId));
    }


    /**
     * Unhide note
     * Restore from soft deleted
     *
     * @param type $resourceId
     * @param type $noteID
     * @return type
     * @throws Exception
     */
    public function restoreNote($resourceId, $noteID)
    {
        $found = $this->getModel()->withTrashed()->where('resource_id', '=', $resourceId)->where('id', '=', $noteID)->first();

        if(is_null($found)) {
            throw new Exception(sprintf("Couldn't find Note #%s for Resource %s", $noteID, $resourceId));
        }

        if($found->restore()) {
            return $found;
        }

        throw new Exception(sprintf("Something went wrong at restore Note #%s for Resource %s", $noteID, $resourceId));
    }


    /**
     * Edit content for note
     *
     * @param type $resourceId
     * @param type $noteID
     * @param array $data
     * @return Note
     * @throws Exception
     */
    public function handleEditContent($resourceId, $noteID, array $data = array())
    {
        $found = $this->getModel()
            ->withTrashed()
            ->where('resource_id', '=', $resourceId)
            ->where('id', '=', $noteID)
            ->with(array('admin' => function($query){
                $query->select('user_id', 'firstname', 'lastname', 'email');
            }))
            ->first();

        if(is_null($found)) {
            throw new Exception(sprintf("Couldn't find Note #%s for Resource %s", $noteID, $resourceId));
        }

        $found->content = array_get($data, 'content', '');

        if($found->save()) {
            return $found;
        }

        throw new Exception(sprintf("Something went wrong at editing Note #%s for Resource %s", $noteID, $resourceId));
    }


    /**
     * As migration will execude SQL code to migrate notes
     * whenever was used html code it is escaped, as it was in latest CRM version
     * This will obtain all notes to certain resource, and decode HTML > then update
     *
     * @param type $resourceID
     * @warning migration feature only
     */
    public static function fixMigratedNotesHtmlCode($resourceID)
    {
        $colection = Note::whereResource($resourceID)->get();

        foreach ($colection as $note) {
            $note->content = html_entity_decode($note->content);
            $note->save();
        }
        
        unset($colection);
    }
}
