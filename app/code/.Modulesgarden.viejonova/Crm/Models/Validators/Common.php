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



namespace Modulesgarden\Crm\Models\Validators;

/**
 * Class for common validator types
 *
 * All functions are STATIC !
 * each function return BOLEAN
 */
class Common
{
    /**
     * is empty value
     *
     * @param mixed $value
     * @return bolean
     */
    public static function isEmpty($value)
    {
        return !empty($value);
    }

    /**
     * is valid email address
     *
     * @param string $value
     * @return bolean
     */
    public static function isEmail($value)
    {
        return filter_var($value, FILTER_VALIDATE_EMAIL);
    }

    /**
     * check if value is positive integer ;)
     *
     * @param string $value
     * @return bolean
     */
    public static function isPositiveNumber($value)
    {
        return filter_var( $value, FILTER_VALIDATE_INT, array(
            'options' => array( 'min_range' => 0)
        )) !== FALSE;
    }

    /**
     * check if value is positive unsigned integer ;)
     *
     * @param string $value
     * @return bolean
     */
    public static function isPositiveUnsignedNumber($value)
    {
        return filter_var( $value, FILTER_VALIDATE_INT, array(
            'options' => array( 'min_range' => 1)
        )) !== FALSE;
    }

    /**
     * check if value is integer ;)
     *
     * @param string $value
     * @return bolean
     */
    public static function isNumber($value)
    {
        return filter_var( $value, FILTER_VALIDATE_INT) !== FALSE;
    }

    /**
     * check if value is bolean
     *
     * @param string $value
     * @return bolean
     */
    public static function isBolean($value)
    {
        return filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * check if value is an a valid email address
     *
     * @param string $value
     * @return bolean
     */
    public static function isValidEmail($value)
    {
        if ( ! filter_var($value, FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        return true;
    }

    /**
     * check if string is valid url address
     *
     * @param string $value
     * @return bolean
     */
    public static function isValidUrl($value)
    {
        return filter_var( $value, FILTER_VALIDATE_URL) !== FALSE;
    }


    /**
     * check if string is valid by given regex
     *
     * @param string $value
     * @param string $regex
     * @return bolean
     */
    public static function isValidByRegex($value, $regex)
    {
        return ( preg_match($regex, $value) == 1);
    }

    /**
     * check if string is valid by given regex
     *
     * @param string $value
     * @param string $regex
     * @return bolean
     */
    public static function validMinCharacters($value, $num)
    {
        return (strlen($value) >= (int)$num);
    }
    
    /**
     * check if string is valid by given regex
     *
     * @param string $value
     * @param string $regex
     * @return bolean
     */
    public static function validMaxCharacters($value, $num)
    {
        return (strlen($value) <= (int)$num);
    }
}