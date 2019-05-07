<?php


namespace App\Interfaces;


interface IDbContext
{
    /**
     * @return object the repository model associated with the instance
     */
    public function GetModel();

    /**
     * @param object $model populated instance of the model
     * @return object|null
     */
    public function Add($model);

    /**
     * @param int|string $id primary key for the record
     * @return null|object
     */
    public function Get($id);

    /**
     * @return null|object[]
     */
    public function GetAll();

    /**
     * @param object $model populated instance of the model with updated info
     * @return object|null
     */
    public function Update($model);

    /**
     * @param int $id unique identifier for the record
     * @return object|null
     */
    public function Delete($id);

    /**
     * @param int $id unique identifier for the record
     * @return bool
     */
    public function HardDelete($id);

    /**
     * @param int $id unique identifier for the record
     * @return object|null
     */
    public function Restore($id);
}