<?php

/* * *************************************************************************************
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
 * ************************************************************************************ */

namespace Modulesgarden\Crm\Integration\Slim;

/**
 * Many responses are handled as json
 * This is a middlewhare that provite it
 */
class JsonView extends \Slim\View
{

    /**
     * Has to be, since it is instance of View, that Slim will handle
     *
     * @param string $template    requested template. This is ommited here. Keeps in this constructor to compatibility with View interface
     * @param array|null $data
     * @param int|string $status
     * @param int|string $status if set, override default status assignment
     * @return void
     */
    public function render($template = '', $data = null)
    {   
        $app = SlimApp::getInstance();
        $data = array_merge($this->all(), (array) $data);

        // flash is taken from Slim framework
        // right now not used
        if (isset($data['flash']) && \is_object($data['flash'])) {
            $flash = $this->data->flash->getMessages();

            if (count($flash)) {
                $data['flash'] = $flash;
            } else {
                unset($data['flash']);
            }
        }

        // set up content type
        // WITH encoding ! damm important
        $app->response->header('Content-Type', 'application/json; charset=utf-8');
        $app->response->body(json_encode($data));
    }

    /**
     * As we want to manually render
     *
     * @param type $template
     * @param type $data
     */
    public function display($template = '', $data = null)
    {
        $app = SlimApp::getInstance();
        $data = array_merge($this->all(), (array) $data);

        // flash is taken from Slim framework
        // right now not used
        if (isset($data['flash']) && \is_object($data['flash'])) {
            $flash = $this->data->flash->getMessages();

            if (count($flash)) {
                $data['flash'] = $flash;
            } else {
                unset($data['flash']);
            }
        }

        // set up content type
        // WITH encoding ! damm important
        $app->response->header('Content-Type', 'application/json; charset=utf-8');
        echo json_encode($data);
    }

}
