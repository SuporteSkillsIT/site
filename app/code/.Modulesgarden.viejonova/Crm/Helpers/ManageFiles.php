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
use \Modulesgarden\Crm\Helpers\ContactImporter;
use \Exception;

/**
 * Class to maintain actions for single lead instance
 */
class ManageFiles
{
    /**
     * Check if directory exist & we got rights to save file there
     * and if not try to create with correct rights
     *
     * @param type $path
     * @throws Exception
     */
    public static function checkDirectory($path)
    {
        if ( ! file_exists($path) )
        {
            if( ! mkdir($path, 0777, true) ) {
                throw new Exception(sprintf("Unable to create directory '%s'", $path));
            }
        } elseif ( ! is_writable($path) ) {
            throw new Exception(sprintf("Directory '%s' is not writable", $path));
        }
    }

    /**
     * Handle to move uploaded file(single) for resource to correct directory
     * used by form for uploading file to resource
     *
     * function does not allow to hangle multiple files
     *
     * @param type $resourceID
     * @param type $file (basically $_FILES)
     * @return arraty with new path/name/oryginal name
     * @throws Exceptiond
    */
    public static function moveUploadedFileForResource($resourceID, $file)
    {
        if(empty($file)) {
            throw new Exception('No file sent.');
        }

        $mainPath    = SlimApp::getInstance()->config('storage.path');
        $filesDir    = SlimApp::getInstance()->config('storage.files');

        $path = $mainPath.'/'.$filesDir;

        // check if folder 'files' exist
        self::checkDirectory($path);
        $path .= '/'.$resourceID;
        self::checkDirectory($path);
        // try to create path for resource id

        // we are intrested ONLY for first file from stack
        $error = array_get($file, 'files.error.0', 0);

        // check if its ok
        if( $error == UPLOAD_ERR_OK )
        {
            $fileOrgName    = array_get($file, 'files.name.0');
            $tmp            = explode(".", $fileOrgName);
            $fileExtension  = end($tmp);
            $fileTmpLocation= array_get($file, 'files.tmp_name.0');
            $fileName       = md5(microtime());
            $fullMovePath   = sprintf('%s/%s.%s', $path, $fileName, $fileExtension);

            if(move_uploaded_file($fileTmpLocation, $fullMovePath))
            {
                return array(
                    'location'  => $fullMovePath,
                    'name'      => $fileName . '.' . $fileExtension,
                    'oryginal'  => $fileOrgName,
                );

            } else {
                throw new Exception('Unable to move uploaded file');
            }
        } else {

            switch ($error)
            {
                case UPLOAD_ERR_NO_FILE:
                    throw new Exception('No file sent.');
                case UPLOAD_ERR_INI_SIZE:
                case UPLOAD_ERR_FORM_SIZE:
                    throw new Exception('Exceeded filesize limit.');
                default:
                    throw new Exception('Unknown errors.');
            }
        }
    }


    public static function makeArrayPerFile(array $files = array())
    {
        $return     = array();
        $errors     = array_get($files, 'files.error', array());
        $validIDs   = array();

        foreach ($errors as $key => $error)  {
            if( $error == UPLOAD_ERR_OK ) {
                $validIDs[] = $key;
            }
        }

        foreach ($validIDs as $key)  {
            $return[] = array(
                'name'      => array_get($files, "files.name.{$key}"),
                'type'      => array_get($files, "files.type.{$key}"),
                'tmp_name'  => array_get($files, "files.tmp_name.{$key}"),
                'error'     => array_get($files, "files.error.{$key}"),
                'size'      => array_get($files, "files.size.{$key}"),
            );
        }

        return $return;
    }


    /**
     * Handle to move uploaded file(single) for resource to correct directory
     * used by form for uploading file to resource
     *
     * function does not allow to hangle multiple files
     *
     * @param type $file (basically $_FILES)
     * @return arraty with new path/name/oryginal name
     * @throws Exceptiond
    */
    public static function moveUploadedFileForImport($files)
    {
        // support only one file, fu*c*k rest
        $file = array_shift(self::makeArrayPerFile($files));

        if(empty($file)) {
            throw new Exception('No file sent.');
        }

        $path       = SlimApp::getInstance()->config('storage.path').'/import';

        // check if folder 'files' exist
        self::checkDirectory($path);

        // we are intrested ONLY for first file from stack
        $error = array_get($file, 'error', 0);

        // check if its ok
        if( $error == UPLOAD_ERR_OK )
        {

            $fileOrgName    = array_get($file, 'name');
            $tmp            = explode(".", $fileOrgName);
            $fileExtension  = end($tmp);
            $fileTmpLocation= array_get($file, 'tmp_name');
            $fullMovePath   = sprintf('%s/import.%s', $path, $fileExtension);

            // check for allowed/not extensin
            if( ! ContactImporter::getInstance()->isAllowedExtension($fileExtension)) {
                throw new Exception('Unsupported file type');
            }

            // first clear dir
            $files = glob($path.'/*');
            foreach($files as $file) {
                if(is_file($file)) {
                    unlink($file);
                }
            }

            if(move_uploaded_file($fileTmpLocation, $fullMovePath))
            {
                return true;
            } else {
                throw new Exception('Unable to move uploaded file');
            }

        } else {

            switch ($error)
            {
                case UPLOAD_ERR_NO_FILE:
                    throw new Exception('No file sent.');
                case UPLOAD_ERR_INI_SIZE:
                case UPLOAD_ERR_FORM_SIZE:
                    throw new Exception('Exceeded filesize limit.');
                default:
                    throw new Exception('Unknown errors.');
            }
        }
    }



    /**
     * Handle to move uploaded file(single) for resource to correct directory
     * used by form for uploading file to resource
     *
     * function does not allow to hangle multiple files
     *
     * @param type $file (basically $_FILES)
     * @return arraty with new path/name/oryginal name
     * @throws Exceptiond
    */
    public static function getHandlerToImportFile()
    {
        $path       = SlimApp::getInstance()->config('storage.path').'/import';

        // check if folder 'files' exist
        self::checkDirectory($path);

        // first clear dir
        $files = glob($path.'/*');
        foreach($files as $file)
        {
            if(is_file($file)) 
            {
                $tmp            = explode(".", $file);
                $fileExtension  = end($tmp);

                if(ContactImporter::getInstance()->isAllowedExtension($fileExtension)) {
                    return $file;
                }
            }
        }

        return false;
    }
}
