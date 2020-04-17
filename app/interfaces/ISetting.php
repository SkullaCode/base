<?php


namespace App\Interfaces;


use Software\Settings\Model;

interface ISetting
{
    /**
     * @return Model
     */
    public function Load();

    /**
     * @param Model $model
     * @return mixed
     */
    public function Save(Model $model);
}