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




/**
 *
 *  Bunch of usefull stuff
 *
 *
 *  LIST
 *      array_flat
 *      pre_print
 *      size_format
 *      str_to_bool
 *      strip_space
 *      full_permissions
 *      array_flip_keys_by
 */

if ( ! function_exists('array_filter_recursive'))
{
    function array_filter_recursive(&$array)
    {
        foreach ( $array as $key => $item )
        {
            is_array( $item ) && $array[$key] = array_filter_recursive( $item );

            if ( empty( $array[$key]) ) {
                unset($array[$key]);
            }
        }
        return $array;
    }
}


if ( ! function_exists('array_flat'))
{
    /**
     * Flat multidimentional array to single level
     * merging keys as route thru dimensions by dot notation
     *
     * @param type $array
     * @param type $prefix
     * @return array
     */
    function array_flat($array, $prefix = '')
    {
        $result = array();

        foreach( $array as $key => $value )
        {
            if ( is_array($value) ) {
                $result = $result + array_flat( $value, $prefix . $key . '.' );
            } else {
                $result[$prefix . $key] = $value;
            }
        }

        return $result;
    }
}



if ( ! function_exists('pre_print'))
{
    /**
     * Wrapper function that makes print_r more browser friendly.
     *
     * @param mixed     $var    what to debug
     * @param string    $title  what title put before
     * @param bolean    $return if true, result string will be returned, not echoed
     * @author Piotr Sarzyński <piotr.sa@modulesgarden.com> < >
     * @return string
     */
    function pre_print($var, $title = '', $return = false)
    {
        $output = '';
        $output .= ($title) ? "<strong>{$title}</strong>" : '';
        $output .= '<pre>';
        $output .= print_r($var, true);
        $output .= '</pre>';

        if($return) {
            return $output;
        } else {
            echo $output;
        }
    }
}

if ( ! function_exists('size_format'))
{
    /**
     * Nice formatting for computer sizes (Bytes).
     *
     * @param   integer $bytes    The number in bytes to format
     * @param   integer $decimals The number of decimal points to include
     * @return  string
     */
    function size_format($bytes, $decimals = 0)
    {
        $bytes = floatval($bytes);
        if ($bytes < 1000) {
            return $bytes . ' B';
        } elseif ($bytes < pow(1000, 2)) {
            return number_format($bytes / 1000, $decimals, '.', '') . ' KB';
        } elseif ($bytes < pow(1000, 3)) {
            return number_format($bytes / pow(1000, 2), $decimals, '.', '') . ' MB';
        } elseif ($bytes < pow(1000, 4)) {
            return number_format($bytes / pow(1000, 3), $decimals, '.', '') . ' GB';
        } elseif ($bytes < pow(1000, 5)) {
            return number_format($bytes / pow(1000, 4), $decimals, '.', '') . ' TB';
        } elseif ($bytes < pow(1000, 6)) {
            return number_format($bytes / pow(1000, 5), $decimals, '.', '') . ' PB';
        } else {
            return number_format($bytes / pow(1000, 5), $decimals, '.', '') . ' PB';
        }
    }
}


if ( ! function_exists('str_to_bool'))
{
    /**
     * Converts many english words that equate to true or false to boolean.
     *
     * Supports 'y', 'n', 'yes', 'no' and a few other variations.
     *
     * @param  string $string  The string to convert to boolean
     * @param  bool   $default The value to return if we can't match any
     *                          yes/no words
     * @return boolean
     */
    function str_to_bool($string, $default = false)
    {
        $yes_words = 'affirmative|all right|aye|indubitably|most assuredly|ok|of course|okay|sure thing|y|yes+|yea|yep|sure|yeah|true|t|on|1|oui|vrai';
        $no_words = 'no*|no way|nope|nah|na|never|absolutely not|by no means|negative|never ever|false|f|off|0|non|faux';
        if (preg_match('/^(' . $yes_words . ')$/i', $string)) {
            return true;
        } elseif (preg_match('/^(' . $no_words . ')$/i', $string)) {
            return false;
        }
        return $default;
    }
}


if ( ! function_exists('strip_space'))
{
    /**
     * Strip all witespaces from the given string.
     *
     * @param  string $string The string to strip
     * @return string
     */
    function strip_space($string)
    {
        return preg_replace('/\s+/', '', $string);
    }
}




if ( ! function_exists('full_permissions'))
{
    /**
     * Returns the file permissions as a nice string, like -rw-r--r-- or false
     * if the file is not found.
     *
     * @param   string $file The name of the file to get permissions form
     * @param   int $perms Numerical value of permissions to display as text.
     * @return  string
     */
    function full_permissions($file, $perms = null)
    {
        if (is_null($perms)) {
            if (!file_exists($file)) {
                return false;
            }
            $perms = fileperms($file);
        }
        if (($perms & 0xC000) == 0xC000) {
            // Socket
            $info = 's';
        } elseif (($perms & 0xA000) == 0xA000) {
            // Symbolic Link
            $info = 'l';
        } elseif (($perms & 0x8000) == 0x8000) {
            // Regular
            $info = '-';
        } elseif (($perms & 0x6000) == 0x6000) {
            // Block special
            $info = 'b';
        } elseif (($perms & 0x4000) == 0x4000) {
            // Directory
            $info = 'd';
        } elseif (($perms & 0x2000) == 0x2000) {
            // Character special
            $info = 'c';
        } elseif (($perms & 0x1000) == 0x1000) {
            // FIFO pipe
            $info = 'p';
        } else {
            // Unknown
            $info = 'u';
        }
        // Owner
        $info .= (($perms & 0x0100) ? 'r' : '-');
        $info .= (($perms & 0x0080) ? 'w' : '-');
        $info .= (($perms & 0x0040) ?
                    (($perms & 0x0800) ? 's' : 'x') :
                    (($perms & 0x0800) ? 'S' : '-'));
        // Group
        $info .= (($perms & 0x0020) ? 'r' : '-');
        $info .= (($perms & 0x0010) ? 'w' : '-');
        $info .= (($perms & 0x0008) ?
                    (($perms & 0x0400) ? 's' : 'x') :
                    (($perms & 0x0400) ? 'S' : '-'));
        // World
        $info .= (($perms & 0x0004) ? 'r' : '-');
        $info .= (($perms & 0x0002) ? 'w' : '-');
        $info .= (($perms & 0x0001) ?
                    (($perms & 0x0200) ? 't' : 'x') :
                    (($perms & 0x0200) ? 'T' : '-'));
        return $info;
    }
}



if ( ! function_exists('array_flip_keys_by'))
{
    /**
     * return mapped array with key from values (array/object)
     *
     * @param  array  $array
     * @param  string $key - specified value using dot notation, ex 'id'
     * @author Piotr Sarzyński <piotr.sa@modulesgarden.com> < >
     * @return mixed
     */
    function array_flip_keys_by(array &$array, $key)
    {
        if (is_null($key) || trim($key) == '') return $array;

        return array_combine(array_map(function($item) use($key) {
            return is_object($item) ? object_get($item, $key) : array_get($item, $key);
        }, $array), $array);

    }
}