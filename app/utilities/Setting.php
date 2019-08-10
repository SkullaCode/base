<?php


namespace App\Utility;


use App\Interfaces\IArrayFunction;
use App\Interfaces\IMedoo;
use App\Interfaces\ISetting;
use Psr\Container\ContainerInterface;
use Software\Provider\SettingsModel;

class Setting implements ISetting
{
    /**
     * @var IMedoo
     */
    private $DB;

    /**
     * @var IArrayFunction
     */
    private $ArrayFunctions;

    /**
     * @var Configuration
     */
    private $Configuration;

    public function __construct(ContainerInterface $c)
    {
        $this->DB = $c->get('Medoo');
        $this->ArrayFunctions = $c->get('ArrayFunctionUtility');
        $this->Configuration = $c->get('ConfigurationUtility');
    }

    /**
     * @return SettingsModel
     */
    public function Load()
    {
        $model = new SettingsModel();
        $settings = $this->DB->select($this->Configuration->SettingsTable,[
            $this->Configuration->SettingsTableKey,
            $this->Configuration->SettingsTableValue
        ]);
        if(is_array($settings) && !empty($settings))
            foreach($settings as $item)
                $model->{$item[$this->Configuration->SettingsTableKey]}
                    = $item[$this->Configuration->SettingsTableValue];

        return $model;
    }

    /**
     * @param SettingsModel $model
     * @return void
     */
    public function Save(SettingsModel $model)
    {
        $original = $this->Load();
        foreach($model as $key => $val)
        {
            if($original->{$key} === $val) continue;
            $this->DB->update($this->Configuration->SettingsTable,[
                $this->Configuration->SettingsTableValue => $val
            ],[
               $this->Configuration->SettingsTableKey => $key
            ]);
        }
    }
}