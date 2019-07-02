<?php


namespace App\DBContext;


use App\Interfaces\IArrayFunction;
use App\Interfaces\IDbContext;
use App\Interfaces\ILogger;
use App\Interfaces\IMedoo;
use Psr\Container\ContainerInterface;

class BaseEntityContext implements IDbContext
{
    /**
     * @var IMedoo
     */
    protected $DBModel;

    /**
     * @var ILogger
     */
    protected $Logger;

    /**
     * @var string
     */
    protected $Table;

    /**
     * @var string
     */
    protected $ID;

    /**
     * @var boolean determines if the primary key should be handled
     * automatically
     */
    protected $AutoID;

    /**
     * @var string[] list of all the fields that can contain null values
     */
    protected $NullFields;

    /**
     * @var IArrayFunction
     */
    protected $ArrayFunctions;

    public function __construct($table, ContainerInterface $c, $settings = [])
    {
        $this->DBModel = $c->get('Medoo');
        $this->Logger = $c->get('LoggerUtility');
        $this->ArrayFunctions = $c->get('ArrayFunctionUtility');
        $this->Table = $table;
        $this->ID = (isset($settings['ID'])) ? $settings['ID'] : 'id';
        $this->NullFields = (isset($settings['NullFields'])) ? $settings['NullFields'] : [];
        $this->AutoID = (isset($settings['AutoID'])) ? (bool)$settings['AutoID'] : true;
    }

    /**
     * @param array $params parameters for function
     * @param callable $func search criteria
     * @return array|null
     */
    public function GetAllWithQuery($params,$func)
    {
        $where = $func($params);
        if(isset($where['LIMIT']))
        {
            if($where['LIMIT'][0] === 0 && $where['LIMIT'][1] === 0)
            {
                unset($where['LIMIT']);
            }
            else
            {
                $where['LIMIT'][0] = ($where['LIMIT'][1] * $where['LIMIT'][0]);
                $where['ORDER'] = [$this->Table.'.'.$this->ID => 'ASC'];
            }
        }

        $res = $this->DBModel->select($this->Table,"*",$where);
        $result = [];
        if(is_array($res) && !empty($res))
        {
            foreach($res as $r)
                $result[] = (object)$r;
        }
        else {}
            //$this->Logger->addError($this->DBModel->error());
        return $result;
    }

    /**
     * @param object $model populated instance of the model
     * @return object|null
     */
    public function Add($model)
    {
        $model = $this->ArrayFunctions->ObjectToArray($model);
        //exclude parameters that will be handled outside of the loop
        $exclude = [
            $this->ID
        ];

        $data = [];
        foreach($model as $key => $val)
        {
            if(in_array($key,$exclude))
                continue;
            $data[$key] = $val;
        }
        if(!$this->AutoID)
            $data[$this->ID] = $model[$this->ID];
        $res = $this->DBModel->insert($this->Table,$data);
        if(is_bool($res)){
            //$this->Logger->addError($this->DBModel->last());
            return null;
        }
        $model = ($res->rowCount() === 1)
            ? ($this->AutoID) ? $this->Get($this->DBModel->id()) : $this->Get($data[$this->ID])
            : null;
        return $model;
    }

    /**
     * @param int|string $id primary key for the record
     * @return null|object
     */
    public function Get($id)
    {
        $where = [$this->Table.'.'.$this->ID => $id];
        $res = $this->DBModel->select($this->Table,"*",$where);

        if(is_array($res) && !empty($res))
            return (object)$res[0];
        return null;
    }

    /**
     * @return null|object[]
     */
    public function GetAll()
    {
        $res = $this->DBModel->select($this->Table,"*");
        $result = [];
        if(is_array($res) && !empty($res))
            foreach($res as $r)
                $result[] = (object)$r;
        else{}
            //$this->Logger->addError($this->DBModel->error());
        return $result;
    }

    /**
     * @param object $model populated instance of the model with updated info
     * @return object|null
     */
    public function Update($model)
    {
        $model = $this->ArrayFunctions->ObjectToArray($model);
        $res = $this->Get($model[$this->ID]);
        if(!is_null($res))
        {
            //capture the original data for referencing
            $original = $res;
            $exclude = [
                $this->ID
            ];
            $data = [];
            foreach($model as $key => $val)
            {
                if(in_array($key,$exclude))
                    continue;

                //only add to update list if the value is different from the original
                //and the value is not null
                if(
                    ($original->{$key} != $val) &&
                    (
                        (!is_null($val)) ||
                        (is_null($val) && in_array($key,$this->NullFields))
                    )
                )
                {
                    $data[$key] = $val;
                }

            }
            $res = $this->DBModel->update($this->Table,$data,[$this->ID => $model[$this->ID]]);
            $model = (!is_bool($res) && !is_null($res) && $res->rowCount() === 1)
                ? $this->Get($model[$this->ID])
                : null;
            if(is_null($model)){

            } //$this->Logger->addError($this->DBModel->error());
            return $model;
        }
        else
        {
            //$this->Logger->addError($this->DBModel->error());
            return null;
        }
        return null;
    }

    /**
     * @param int $id unique identifier for the record
     * @return bool
     */
    public function Delete($id)
    {
        return $this->HardDelete($id);
    }

    /**
     * @param int $id unique identifier for the record
     * @return bool
     */
    public function HardDelete($id)
    {
        $res = $this->DBModel->delete($this->Table,[$this->ID => $id]);
        if($res->rowCount() === 1)
            return true;
        //$this->Logger->addError($this->DBModel->error());
        return false;
    }

    public function Restore($id)
    {
       return true;
    }
}