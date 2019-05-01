<?php

namespace App\Interfaces;

use App\Interfaces\Context\IDbContext;

abstract class DbContext implements IDbContext
{
    /**
     * @return object the repository model associated with the instance
     */
    public abstract function GetModel();

    /**
     * @param int|string|array $id unique identifier for the record
     * @param callable|string $func
     * @return null|object
     */
    protected abstract function GetWithQuery($id,$func);

    /**
     * @param array $params parameters for function
     * @param callable $func search criteria
     * @return array|null
     */
    protected abstract function GetAllWithQuery($params,$func);

    /**
     * @param array $params
     * @param callable $func
     * @return bool
     */
    protected abstract function DeleteAllWithQuery($params,$func);

    /**
     * @param array $params
     * @param callable $func
     * @return bool
     */
    protected abstract function HardDeleteAllWithQuery($params,$func);

    /**
     * @param object $model populated instance of the model
     * @return object|null
     */
    public abstract function Add($model);

    /**
     * @param int|string $id
     * @return null|object
     */
    public abstract function Get($id);

    public abstract function GetAll();

    /**
     * @param object $model populated instance of the model with updated info
     * @return object|null
     */
    public abstract function Update($model);

    /**
     * @param int $id unique identifier for the record
     * @return object|null
     */
    public abstract function Delete($id);

    /**
     * @param int $id unique identifier for the record
     * @return bool
     */
    public abstract function HardDelete($id);

    /**
     * @param int $id unique identifier for the record
     * @return object|null
     */
    public abstract function Restore($id);
}