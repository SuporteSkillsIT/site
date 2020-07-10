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

use \Modulesgarden\Crm\Services\Language;

use \Modulesgarden\Crm\Repositories\FieldDatas;

use \Modulesgarden\Crm\Models\Field;
use \Modulesgarden\Crm\Models\Resource;

use \PHPExcel;
use \PHPExcel_Settings;
use \PHPExcel_IOFactory;

use \Carbon\Carbon;
use \Exception;

/**
 * Export Resources to various format
 * baseds by library PHPExcel (http://www.codeplex.com/PHPExcel)
 *
 * @author Piotr Sarzyński <piotr.sa@modulesgarden.com> < >
 */
class ContactExporter
{
    /**
     * Keep single instance of translation object
     * We do not want to create many many translators object's
     *
     * @var Lang instance
     */
    private static $instance;

    /**
     * Keep instance of PHPExcel document
     * @var PHPExcel
     */
    private $excel      = null;

    /**
     * Requested format to genenerate exported data
     * @var string
     */
    private $format     = null;

    /**
     * container for parsed to single array Resource with active fields
     * @var array
     */
    private $rows       = array();

    /**
     * Single array with First row initializers.
     * Column names, like Name/Created Date/Status/Admin ID etc
     * check ContactExporter::getHeaderRow method
     * @var array
     */
    private $header     = array();

    /**
     * Keep collection of all FieldData that will be renderred in first row
     * Basyd by this we sort resource data
     * @var eloquent collection
     */
    private $fields     = array();

    /**
     * Allowed formats to generate
     * Key is format identifier
     * Value is PHPExcel Writer Interface to handle that format
     * @var array
     */
    private static $allowedFileExtension = array(
        'csv'   => 'CSV',
        'xls'   => 'Excel5',
        'xlsx'  => 'Excel2007',
        'ods'   => 'OpenDocument',
        'pdf'   => 'PDF',
    );


    /**
     * Constuct
     * @Singletron pattern
     */
    private function __construct() {}


    /**
     * Disable clones
     * @Singletron pattern
     */
    private function __clone() {}


    /**
     * Get Instance
     * @Singletron pattern
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


    /**
     * it may take long time in marginal cases
     * so lets update some variables
     */
    protected function setNewLimits()
    {
        @ini_set('memory_limit', '512M');
        @ini_set('max_execution_time', 0);
        @set_time_limit(0);
    }

    /**
     * Plain check if we can handle requested extension
     *
     * @param string $type
     * @return boolean
     */
    public function isAllowedExtension($type){
        return array_key_exists(strtolower($type), self::$allowedFileExtension);
    }


    /**
     * PHPExcel Writer Interface name for certain field type
     *
     * @param string $type
     * @return boolean
     */
    public function getExtensionWriter($type)
    {
        if(!$this->isAllowedExtension($type)) {
            return false;
        }

        return self::$allowedFileExtension[strtolower($type)];
    }


    /**
     * Right after create new export document
     * this function handle Set up basic file information
     * Could be usefull some day
     *
     * @todo: handle import few data from global whmcs settings
     */
    public function setUpCreatorData()
    {
        $this->excel->getProperties()
            ->setCreator("Piotr Sarzyński")
            ->setLastModifiedBy("Piotr Sarzyński")
            ->setTitle("ModulesGarden CRM Exported Data")
            ->setSubject("ModulesGarden CRM Exported Data")
            ->setDescription("ModulesGarden CRM Exported Data. generated using PHP classes");
//            ->setKeywords("office 2007 openxml php")
//            ->setCategory("Test result file");
    }


    /**
     * Using PHPExcel render file that we generate to php://output (browser)
     * Just remember that this will trigger exit function to close script.
     *
     * Before trigger this function controller will set up proper headers for requested file type
     * and to proper handling browser to generated type, headers are MUST BE.
     *
     * @notice: seting up headers might have been in this class, but lets separate that from controller
     *
     * @throws Exception
     */
    public function render()
    {
        // just double check
        if($this->format == 'pdf')
        {
            $rendererName = PHPExcel_Settings::PDF_RENDERER_DOMPDF;
            $rendererLibraryPath = SlimApp::getInstance()->config('appInternalAppDir').'/vendor/dompdf/dompdf';

            if (!\PHPExcel_Settings::setPdfRenderer(PHPExcel_Settings::PDF_RENDERER_DOMPDF, $rendererLibraryPath)) {
                throw new Exception('Unable to load domPDF library');
            }
        }

        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, $this->getExtensionWriter($this->format));
        $objWriter->save('php://output');
        exit;
    }


    /**
     * Main function that trigger
     *
     * @param string $format - for what format we want generated report
     */
    public function generateDataToExport($format)
    {
        $this->setNewLimits();

        // Create new PHPExcel object
        $this->excel    = new PHPExcel();
        $this->format   = $format;

        $this->fieldDataRepo = new FieldDatas();

        // init values
        $this->setUpCreatorData();

        // obtain columns
        $this->getHeaderRow();
        // obtain actual data
        $this->getDataRows();

        // have to be here
        $this->excel->setActiveSheetIndex(0);
        // first row is special, contain record column names
        $this->excel->getActiveSheet()->fromArray(array($this->header), NULL, 'A1');
        // this will render actual data
        $this->excel->getActiveSheet()->fromArray($this->rows, NULL, 'A2');
    }


    /**
     * Set up $this->header array with correct first row columns and names
     * since we want to know what column is what :D
     */
    public function getHeaderRow()
    {
        $tmp = array(
            'Name',
            'Type',
            'Status',
            'Priority',
            'Email',
            'Phone',
            'Admin ID',
            'Client ID',
            'Ticket ID',
            'Update Date',
            'Creation Date',
        );
        // with possible options to filter
        $this->fields = Field::joinOptions()->activeFields()->orderred()->get();

        // make from it nice array
        // key is field id, value is field name
        // array is sorted, and filterred
        // this apply only for first row
        $customFields = $this->fields->map(function($item, $key) {
            return $item->name;
        })->toArray();


        $this->header = array_merge($tmp, $customFields);
    }


    /**
     * Plain return translated priority based by current admin lang
     *
     * @param mixed $priority
     * @return string
     */
    public function mapPriorityToLang($priority)
    {
        if((int)$priority >= 0 && (int)$priority <= 4 && !is_null($priority)) {
            return Language::translate('priority.'.$priority);
        }
        
        return Language::translate('priority.0');
    }


    /**
     * Make same format for Carbon objects, from eloquent
     *
     * @param Carbon $date
     * @return string
     */
    public function parseCarbonObjectToString(Carbon $date)
    {
        return $date->toDateTimeString();
    }


    /**
     * Single resource got parsed data, we got some static, some custom
     * custom might not be set, or might be for fields we dont render
     * This will sync custom fields renderred in header with actually values from resource and return it in correct order
     *
     * if not exist value > null :)
     *
     * @param array $static
     * @param array $custom
     * @return array
     */
    public function syncStaticFieldsWithCustom($static, $custom)
    {
        $cf = $this->fields->map(function($item) use($custom){
            return (array_key_exists($item->id, $custom) ? $custom[$item->id] : null);
        })->toArray();
        

        return array_merge($static, $cf);
    }


    /**
     * Parse single resource to generated ROW informations
     *
     * @param Resource $r
     * @return array
     */
    public function parseResourceToRow(Resource $r)
    {
        $tmp = array(
            (empty($r->name)            ? ' ' : $r->name),
            (empty($r->type->name)      ? ' ' : $r->type->name),
            (empty($r->status->name)    ? ' ' : $r->status->name),
            $this->mapPriorityToLang($r->priority),
            (empty($r->email)           ? ' ' : $r->email),
            (empty($r->phone)           ? ' ' : $r->phone),
            (empty($r->admin_id)        ? ' ' : $r->admin_id),
            (empty($r->client_id)       ? ' ' : $r->client_id),
            (empty($r->ticket_id)       ? ' ' : $r->ticket_id),
            $this->parseCarbonObjectToString($r->updated_at),
            $this->parseCarbonObjectToString($r->created_at),
        );

        $customfields = FieldDatas::toArrayForExport($r->fieldDatas);

        return $this->syncStaticFieldsWithCustom($tmp, $customfields);
    }

    /**
     * Obtain ALL Resources (not deleted at > from Archive)
     * and parse each one to new single row array
     * in $this->rows
     *
     * @notice:
     *  This is place when we can trigger some filters to eloquent
     *  or orders by mechanism
     *  or some limitations
     */
    public function getDataRows()
    {
        $list = Resource::with(array('type', 'status', 'fieldDatas' => function($query) {
            $query->joinAssignedOptionsAndOption()->joinField()->withTrashed();
        }))->get();

        foreach($list as $item) {
            $this->rows[] = $this->parseResourceToRow($item);
        }
    }
}
