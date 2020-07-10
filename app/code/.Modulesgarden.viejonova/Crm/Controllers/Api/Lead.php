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


namespace Modulesgarden\Crm\Controllers\Api;

use Modulesgarden\Crm\Controllers\Source\AbstractController;


/**
 * Class to maintain actions for single lead instance
 */
class Lead extends AbstractController
{
    /**
     * Get summary information to render lead header panel
     * on each of lead > page
     *
     * @param int $id
     * @return array
     */
    public function getLeadHeaderData($id)
    {
        $variables = array(
            'id'          => $id,
            'name'        => 'John Doe',
            'description' => 'This guy is sick!',
            'status'      => 'Active',
            'type'        => 'lead',
            'updated'     => '12:35 03-08-2015',
            'created'     => '12:35 03-08-2015',
        );

        return $this->returnData($variables);
    }


    /**
     * Get summary information to render lead header panel
     * on each of lead > page
     *
     * @param int $id
     * @return array
     */
    public function getLeadSummaryData($id)
    {

        $customFields = array(
                array(
                    'name' => 'Main Details',
                    'hidden' => false,
                    'description' => false,
                    'type' => 'customFields',
                    'data'=> array(
                        1 => array(
                            'name' => 'Assigned Client',
                            'value' => 'John Doe',
                            'type' => 'text',
                        ),
                        2 => array(
                            'name' => 'Email',
                            'value' => 'john@doe.com',
                            'type' => 'text',
                        ),
                        3 => array(
                            'name' => 'Phone number',
                            'value' => '559-088-434',
                            'type' => 'text',
                        ),
                        4 => array(
                            'name' => 'Company',
                            'value' => 'Redswitches',
                            'type' => 'text',
                        ),
                        5 => array(
                            'name' => 'Role',
                            'value' => 'CEO',
                            'type' => 'text',
                        ),
                    ),
                ),
                array(
                    'name' => 'COMPANY',
                    'hidden' => false,
                    'description' => false,
                    'type' => 'customFields',
                    'fields'=> array(
                        1 => array(
                            'name' => 'Number of Employes',
                            'value' => '666',
                            'type' => 'text',
                        ),
                        2 => array(
                            'name' => 'Industry',
                            'value' => 'VPS owner',
                            'type' => 'text',
                        ),
                        3 => array(
                            'name' => 'Webpage',
                            'value' => 'Redswitches.com',
                            'type' => 'text',
                        ),
                        4 => array(
                            'name' => 'Facebook',
                            'value' => 'facebook.com/redswitches',
                            'type' => 'text',
                        ),
                        5 => array(
                            'name' => 'Twitter',
                            'value' => '@redswitches',
                            'type' => 'text',
                        ),
                    ),
                ),
            );


        $followups = array(
                array(
                    'name' => 'Follow-ups',
                    'hidden' => false,
                    'description' => 'upcoming one',
                    'type' => 'followups',
                    'data'=> array(
                        1 => array(
                            'name' => 'Assigned Client',
                            'value' => 'John Doe',
                            'type' => 'text',
                        ),
                        2 => array(
                            'name' => 'Email',
                            'value' => 'john@doe.com',
                            'type' => 'text',
                        ),
                        3 => array(
                            'name' => 'Phone number',
                            'value' => '559-088-434',
                            'type' => 'text',
                        ),
                        4 => array(
                            'name' => 'Company',
                            'value' => 'Redswitches',
                            'type' => 'text',
                        ),
                        5 => array(
                            'name' => 'Role',
                            'value' => 'CEO',
                            'type' => 'text',
                        ),
                    ),
                ),
            );

        $logs = array(
                array(
                    'name' => 'Logs',
                    'hidden' => false,
                    'description' => 'last events',
                    'type' => 'logs',
                    'data'=> array(
                        1 => array(
                            'name' => 'Assigned Client',
                            'value' => 'John Doe',
                            'type' => 'text',
                        ),
                        2 => array(
                            'name' => 'Email',
                            'value' => 'john@doe.com',
                            'type' => 'text',
                        ),
                        3 => array(
                            'name' => 'Phone number',
                            'value' => '559-088-434',
                            'type' => 'text',
                        ),
                        4 => array(
                            'name' => 'Company',
                            'value' => 'Redswitches',
                            'type' => 'text',
                        ),
                        5 => array(
                            'name' => 'Role',
                            'value' => 'CEO',
                            'type' => 'text',
                        ),
                    ),
                ),
            );

        $emails = array(
                array(
                    'name' => 'Emails',
                    'hidden' => false,
                    'description' => 'last messages',
                    'type' => 'emails',
                    'data'=> array(
                        1 => array(
                            'name' => 'Assigned Client',
                            'value' => 'John Doe',
                            'type' => 'text',
                        ),
                        2 => array(
                            'name' => 'Email',
                            'value' => 'john@doe.com',
                            'type' => 'text',
                        ),
                        3 => array(
                            'name' => 'Phone number',
                            'value' => '559-088-434',
                            'type' => 'text',
                        ),
                        4 => array(
                            'name' => 'Company',
                            'value' => 'Redswitches',
                            'type' => 'text',
                        ),
                        5 => array(
                            'name' => 'Role',
                            'value' => 'CEO',
                            'type' => 'text',
                        ),
                    ),
                ),
            );

        $toReturn = array_merge($customFields, $followups, $emails, $logs);

        return $this->returnData($toReturn);
    }
}
