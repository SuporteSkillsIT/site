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

use \Modulesgarden\Crm\Integration\Slim\SlimApp;
use \Modulesgarden\Crm\Models\Magento\EmailTemplates;
use \Modulesgarden\Crm\Models\Validators\Common;
use \Modulesgarden\Crm\Models\Field;
use \Modulesgarden\Crm\Models\EmailLog;
use \Modulesgarden\Crm\Models\Resource;
use \Modulesgarden\Crm\Models\ResourceType;
use \Modulesgarden\Crm\Models\FieldStatus;
use \Modulesgarden\Crm\Repositories\PermissionRoles;
use \Modulesgarden\Crm\Services\Language;

use \Modulesgarden\Crm\Helpers\ManageFiles;

use \Illuminate\Database\Capsule\Manager as DB;
use \Carbon\Carbon;
use \Exception;

use \PHPExcel_IOFactory;


/**
 * Use with SMS Center module cooperation
 *
 * @author Piotr Sarzyński <piotr.sa@modulesgarden.com> < >
 */
class ContactImporter
{
    /**
     * Keep single instance of translation object
     * We do not want to create many many translators object's
     *
     * @var Lang instance
     */
    private static $instance;

    private static $allowedFileExtension = array(
        'csv'   => 'CSV',
        'xls'   => 'Excel5',
        'xlsx'  => 'Excel2007',
        'ods'   => 'OpenDocument',
    );


    // with possible options to filter
    private $fields             = array();
    private $contactTypes       = array();
    private $contactStatuses    = array();
    private $contactPriorities  = array();

    private $mapStatic       = array();
    private $mapCustomFields = array();



    /**
     * Constuct
     */
    private function __construct() {}


    /**
     * Disable clones
     */
    private function __clone() {}


    /**
     * Keep Singletron pattern
     *
     * @return Lang object
     */
    public static function getInstance()
    {
        // singletron!
        if( null === static::$instance )
        {
            static::$instance   = new static;
        }

        return static::$instance;
    }


    protected function setNewLimits()
    {
        @ini_set('memory_limit', '512M');
        @ini_set('max_execution_time', 0);
        @set_time_limit(0);
    }

    public function isAllowedExtension($type){
        return array_key_exists(strtolower($type), self::$allowedFileExtension);
    }

//	public function toArray($nullValue = null, $calculateFormulas = true, $formatData = true, $returnCellRef = false) {
//        // Garbage collect...
//        $this->garbageCollect();
//
//        //    Identify the range that we need to extract from the worksheet
//        $maxCol = $this->getHighestColumn();
//        $maxRow = $this->getHighestRow();
//        // Return
//		return $this->rangeToArray(	'A1:'.$maxCol.$maxRow,
//									$nullValue, $calculateFormulas, $formatData, $returnCellRef);
//    }


    public function getSummaryForUploadedFile()
    {
        $return = array();

        $file = ManageFiles::getHandlerToImportFile();

        if( $file === false ) {
            return $return;
        }

        $this->setNewLimits();

        // LOAD FILE
        $this->excel = PHPExcel_IOFactory::load($file);
        
        $rows = ($this->excel->getActiveSheet()->getHighestRow() - 1);

        if(is_numeric($rows)) {
            $return['rows'] = $rows;
        }

        return $return;
    }


    public function parseFirstRow()
    {
        $this->maxCol = $this->excel->getActiveSheet()->getHighestColumn();

        $nullValue          = null;
        $calculateFormulas  = true;
        $formatData         = true;
        $returnCellRef      = false;

        $header = $this->excel->getActiveSheet()->rangeToArray("A1:{$this->maxCol}1", $nullValue, $calculateFormulas, $formatData, $returnCellRef);
        $header = array_shift($header);

        $this->determinateColumnsOrder($header);



        $types      = ResourceType::withTrashed()->get();
        $statuses   = FieldStatus::withTrashed()->get();

        foreach ($types as $type) {
            $this->contactTypes[$type->name] = $type->id;
        }
        foreach ($statuses as $status) {
            $this->contactStatuses[$status->name] = $status->id;
        }

        $this->contactPriorities = array(
            Language::translate('priority.1') => 1,
            Language::translate('priority.2') => 2,
            Language::translate('priority.3') => 3,
            Language::translate('priority.4') => 4,
        );

    }

    public function determinateColumnsOrder($row)
    {
        // with possible options to filter
        $this->fields = Field::joinOptions()->withTrashed()->get();

        $staticFieldNames = array(
            'Name'          => 'name',
            'Type'          => 'type_id',
            'Priority'      => 'priority',
            'Email'         => 'email',
            'Phone'         => 'phone',
            'Admin ID'      => 'admin_id',
            'Client ID'     => 'client_id',
            'Ticket ID'     => 'ticket_id',
            'Update Date'   => 'updated_at',
            'Creation Date' => 'created_at',
            'Status'        => 'status_id',
        );

        foreach($row as $index => $columnName)
        {
            $filterred = trim($columnName);

            if(array_key_exists($filterred, $staticFieldNames)) {
                array_set($this->mapStatic, $staticFieldNames[$filterred], $index);
            }

            // not static, lets search in dynamic

            foreach($this->fields as $field)
            {
                if($filterred == $field->name) {
                    array_set($this->mapCustomFields, $field->id, $index);
                }
            }
        }
    }

    public function runImportFromFile()
    {
        $return = array();

        $file = ManageFiles::getHandlerToImportFile();

        if( $file === false ) {
            throw new Exception('File not found');
        }

        $this->setNewLimits();

        // LOAD FILE
        $this->excel = PHPExcel_IOFactory::load($file);
        $this->parseFirstRow();


        $maxRow     = $this->excel->getActiveSheet()->getHighestRow();
        $rows       = $this->excel->getActiveSheet()->rangeToArray("A2:{$this->maxCol}{$maxRow}", null, false, false, false);
        $counter    = 0;

        foreach ($rows as $row)
        {

            try {
                $staticData     = $this->mapRowByValues($row, $this->mapStatic);
                $dynamicFieds   = $this->mapRowByValues($row, $this->mapCustomFields);

                // addjustments
                $staticData     = $this->parseStaticVariables($staticData);

                $newResource = new Resource($staticData);
                $newResource->save();

                foreach ($this->fields as $field)
                {
                    try{
                        $field->createNewDataFromMigrationData($newResource, array_get($dynamicFieds, $field->id, array()));
                    } catch (Exception $e) {
                        // łaza
                    }
                }


                if($newResource->id) {
                    $counter++;
                }

            } catch (Exception $e) {
                // łaza
            }
        }

        return $counter;
    }


    public function mapRowByValues($data, $map)
    {
        $result = array();

        foreach ($map as $name => $key) {
            $result[$name] = array_get($data, $key, null);
        }

        return $result;
    }


    public function parseStaticVariables($data = array())
    {
        $updated    = Carbon::parse(array_get($data, 'update_date', false));
        $created    = Carbon::parse(array_get($data, 'creation_date', false));
        $type_id    = null;
        $priority   = null;
        $status_id  = null;

        // for integrity in db
        $client_id = array_get($data, 'client_id', null);
        if(!is_numeric($client_id) || $client_id == 0) {
            $client_id = null;
        }
//        $ticket_id = array_get($data, 'ticket_id', null);
//        if(!is_numeric($ticket_id) || $ticket_id == 0) {
//            $ticket_id = null;
//        }
        $admin_id = array_get($data, 'admin_id', null);
        if(!is_numeric($admin_id) || $admin_id == 0) {
            $admin_id = null;
        }


        if(isset($this->contactTypes[$data['type_id']])) {
            $type_id = $this->contactTypes[$data['type_id']];
        }
        if(isset($this->contactPriorities[$data['priority']])) {
            $priority = $this->contactPriorities[$data['priority']];
        }
        if(isset($this->contactStatuses[$data['status_id']])) {
            $status_id = $this->contactStatuses[$data['status_id']];
        }



        return array(
            // fillable
            'name'          => array_get($data, 'name'),
            'email'         => array_get($data, 'email'),
            'phone'         => array_get($data, 'phone'),

            // relations
            'type_id'       => $type_id,
            'status_id'     => $status_id,
            'priority'      => $priority,

            // relations
            'client_id'     => $client_id,
//            'ticket_id'     => $ticket_id,
            'admin_id'      => $admin_id,

            // dates
            'created_at'    => $created,
            'updated_at'    => $updated,
            'deleted_at'    => null,
        );
    }










}
