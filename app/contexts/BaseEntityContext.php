<?php


namespace App\DBContext;


use App\Interfaces\IArrayFunction;
use App\Interfaces\ILogger;
use App\Interfaces\IMedoo;
use Psr\Container\ContainerInterface;

class BaseEntityContext
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
     * @var boolean determines if the primary key should be handled
     * or not
     */
    protected $HandleID;

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
        $this->AutoID = (isset($settings['AutoID'])) ? (bool)$settings['AutoID'] : true;
        $this->HandleID = (isset($settings['HandleID'])) ? (bool)$settings['HandleID'] : true;
    }

    /**
     * @param array $params parameters for function
     * @param callable $func search criteria
     * @return array
     */
    protected function GetAllWithQuery($params,$func)
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
        else
        {
            //$this->Logger->addError($this->DBModel->error());
        }
        return $result;
    }

    /**
     * @param object $model populated instance of the model
     * @return object|bool
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
        if($this->HandleID)
        {
            if(!$this->AutoID)
                $data[$this->ID] = $model[$this->ID];
        }
        $res = $this->DBModel->insert($this->Table,$data);
        if(is_bool($res)){
            //$this->Logger->addError($this->DBModel->last());
            return false;
        }
        if($this->HandleID)
        {
            $model = ($res->rowCount() === 1)
                ? ($this->AutoID) ? $this->Get($this->DBModel->id()) : $this->Get($data[$this->ID])
                : null;
            return $model;
        }
        return true;
    }

    /**
     * @param int|string $id primary key for the record
     * @return bool|object
     */
    public function Get($id)
    {
        $where = [$this->Table.'.'.$this->ID => $id];
        $res = $this->DBModel->select($this->Table,"*",$where);

        if(is_array($res) && !empty($res))
            return (object)$res[0];
        return false;
    }

    /**
     * @return object[]
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
     * @param array $where identifier for update
     * @return bool
     */
    protected function Update($model,$where)
    {
        $model = $this->ArrayFunctions->ObjectToArray($model);
        if(is_null($where))
        {
            if($this->HandleID)
                $where = [$this->ID => $where[$this->ID]];
            else
                return false;
        }
        $res = $this->DBModel->update($this->Table,$model,$where);
        return (is_bool($res)) ? $res : true;
    }

    /**
     * @param array $where
     * @return bool
     */
    protected function Delete($where = null)
    {
        if(is_null($where))
        {
            if($this->HandleID)
                $where = [$this->ID => $where[$this->ID]];
            else
                return false;
        }
        $res = $this->DBModel->delete($this->Table,$where);
        if($res->rowCount() === 1)
            return true;
        //$this->Logger->addError($this->DBModel->error());
        return false;
    }
}