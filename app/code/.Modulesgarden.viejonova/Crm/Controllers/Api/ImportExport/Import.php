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


namespace Modulesgarden\Crm\Controllers\Api\ImportExport;

use Modulesgarden\Crm\Controllers\Source\AbstractController;

use Modulesgarden\Crm\Helpers\ManageFiles;
use Modulesgarden\Crm\Helpers\ContactImporter;


class Import extends AbstractController
{

    public function uploadFile()
    {
        try {

            if( is_array($_FILES) && !empty($_FILES) ) {
                $files   = $_FILES;
            } else {
                $files   = array();
            }

            ManageFiles::moveUploadedFileForImport($files);

            return $this->returnData(array(
                'status' => 'success',
                'msg'    => 'File has been uplaoded',
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


    public function getFileSummaryFile()
    {
        try{

            return $this->returnData(ContactImporter::getInstance()->getSummaryForUploadedFile());

        } catch (Exception $ex) {
            // some logging errors mechanism
            $this->app->response->setStatus(409);
            return $this->returnData(array(
                'status' => 'error',
                'msg'    => $ex->getMessage(),
            ));
        }
    }


    public function importContacts()
    {

        try{
            $howmany = ContactImporter::getInstance()->runImportFromFile();

            return $this->returnData(array(
                'status' => 'success',
                'msg'    => sprintf('%d Contacts has been imported', $howmany),
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
