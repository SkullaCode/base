<?php


namespace App\Utility;


use App\Interfaces\IArrayFunction;
use App\Interfaces\IMedoo;
use App\Interfaces\ISetting;
use Psr\Container\ContainerInterface;
use Software\Settings\Model;

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
     * @return Model
     */
    public function Load()
    {
        $model = new Model();
        $settings = $this->DB->select($this->Configuration->SettingsTable,[
            $this->Configuration->SettingsTableKey,
            $this->Configuration->SettingsTableValue
        ]);
        if(is_array($settings) && !empty($settings))
            foreach($settings as $item)
                if(property_exists($model,$item[$this->Configuration->SettingsTableKey]))
                    $model->{$item[$this->Configuration->SettingsTableKey]}
                        = $item[$this->Configuration->SettingsTableValue];

        return $model;
    }

    /**
     * @param Model $model
     * @return void
     */
    public function Save(Model $model)
    {
        $original = $this->Load();
        foreach($model as $key => $val)
        {
            if(!$this->DB->has($this->Configuration->SettingsTable,[$this->Configuration->SettingsTableKey => $key]))
            {
                $this->DB->insert($this->Configuration->SettingsTable,[
                    $this->Configuration->SettingsTableKey      =>  $key,
                    $this->Configuration->SettingsTableValue    =>  $val
                ]);
            }
            else
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
}