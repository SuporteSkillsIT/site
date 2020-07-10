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
        


namespace Modulesgarden\Crm\Repositories\Source;

use \Illuminate\Database\Eloquent\Model;
use Modulesgarden\Crm\Repositories\Source\RepositoryException;

abstract class AbstractRepository
{
    /**
     * Container for created model object
     * 
     * @var
     */
    protected $model;


    /**
     * Force to Specify Model class name in repository
     *
     * @return mixed
     */
    abstract function determinateModel();

    
    /**
     * @throws \Bosnadev\Repositories\Exceptions\RepositoryException
     */
    public function __construct() 
    {
        $this->makeModel();
    }

    /**
     * Return model
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * Make model object
     *
     * @return \Illuminate\Database\Eloquent\Model
     * @throws Modulesgarden\Crm\Repositories\Source\RepositoryException
     */
    public function makeModel()
    {
        // incepcion :D
        $this->model = $this->determinateModel();
        $this->model = new $this->model;

        if ( ! $this->model instanceof Model ) {
            throw new RepositoryException("Class {$this->model()} must be an instance of Illuminate\\Database\\Eloquent\\Model");
        }

        return $this->model;
    }



    /**
     * Return all elements from model
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function all()
    {
        return $this->model->all();
    }


    /**
     * Delete the model from the database.
     *
     * @return bool|null
     */
    public function delete($id)
    {
        return $this->model->find($id)->delete();
    }

    /**
     * Force to delete resource from Database
     * This is wrapper for Eloquent action
     *
     * @param type $id
     * @return void
     */
    public function forceDelete($id)
    {
        return $this->model->find($id)->forceDelete();
    }

    /**
     * return single model follow by id
     *
     * @param type $id
     * @return Illuminate\Database\Eloquent\Model
     */
    public function find($id)
    {
        return $this->model->find($id);
    }

    /**
     * Wrap model to order functionality
     *
     * @param type $column
     * @param type $direction
     * @return Illuminate\Database\Eloquent\Model
     */
    public function orderBy($column, $direction = 'ASC')
    {
        return $this->model->orderBy($column, $direction);
    }

    /**
     * Wrap model to where functionality
     *
     * @param type $column
     * @param type $direction
     * @return Illuminate\Database\Eloquent\Model
     */
    public function where($column, $operand, $value)
    {
        return $this->model->where($column, $operand, $value);
    }

    /**
     * Create in DB
     *
     * @param type $data
     * @return type
     */
    public function create(array $data)
    {
        return $this->model->create($data);
    }

}
