<?php


namespace App\Interfaces;


use Software\Provider\SettingsModel;

interface ISetting
{
    /**
     * @return SettingsModel
     */
    public function Load();

    /**
     * @param SettingsModel $model
     * @return mixed
     */
    public function Save(SettingsModel $model);
}