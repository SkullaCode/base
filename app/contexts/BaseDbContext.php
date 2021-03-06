<?php


namespace App\DBContext;

use App\Interfaces\DbContext;
use App\Interfaces\ILogger;
use App\Interfaces\IMedoo;
use App\Model\AuditCM;
use App\Model\BaseCM;
use App\Utility\Configuration;
use App\Utility\Session;
use DateTime;
use Exception;
use Psr\Container\ContainerInterface;
use ReflectionObject;
use ReflectionProperty;
use Software\Entity\User\Constants\Status;

class BaseDbContext extends DbContext
{
    /**
     * @var BaseCM
     */
    protected $Model;

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
     * @var string name of the status column
     */
    protected $TableStatus;

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
     * @var string name of the field to update with timestamp on creation
     */
    protected $CTS;

    /**
     * @var string name of the field to update with timestamp on creation and modification
     */
    protected $MTS;

    /**
     * @var string name of the field to update with username on creation
     */
    protected $CBY;

    /**
     * @var string name of the field to update with username on modification
     */
    protected $MBY;

    /**
     * @var string name of the currently logged in application user
     */
    protected $AppUser;

    /**
     * @var array used if there are joins
     */
    protected $Join;

    /**
     * @var string[] list of all the fields that can contain null values
     */
    protected $NullFields;

    /**
     * @var array name transformation for model properties
     */
    protected $Mapper;

    /**
     * @var boolean remove item or change status
     */
    protected $SoftDelete;

    /**
     * @var array runtime model updates
     */
    protected $Transformer;

    public function __construct($model, $table, ContainerInterface $c)
    {
        /**
         * @var Configuration $config
         */
        $config = $c->get('ConfigurationUtility');

        /**
         * @var Session $session
         */
        $session = $c->get('SessionUtility');
        $this->Model = $model;
        $this->DBModel = $c->get('Medoo');
        $this->Logger = $c->get('LoggerUtility');
        $this->Table = $table;
        $this->TableStatus = 'Status';
        $this->ID = 'ID';
        $this->NullFields = [];
        $this->Join = [];
        $this->Mapper = [];
        $this->Transformer = [];
        $this->AutoID = true;
        $this->CTS = 'cts';
        $this->MTS = 'mts';
        $this->CBY = 'cby';
        $this->MBY = 'mby';
        $UID = $session->GetItem($config->AppUserID);
        $this->AppUser = (!is_null($UID)) ? $UID : $config->DefaultAppUser;
        $this->SoftDelete = false;
    }

    /**
     * @return BaseCM the repository model associated with the instance
     */
    public function GetModel()
    {
        return clone $this->Model;
    }

    /**
     * @param string $f
     * @return string
     */
    protected function Field($f)
    {
        $name = $f;
        $f = $this->Map($f);
        if(is_array($f))
            if(count($f) === 2)
                $f = $f[0];
            elseif(count($f) === 1)
                $f = $name;
            else
                $f = $name;
        return $this->Table.'.'.$f;
    }

    /**
     * @return ReflectionProperty[]
     */
    protected function GetModelPublicProperties()
    {
        return (new ReflectionObject($this->GetModel()))->getProperties(ReflectionProperty::IS_PUBLIC);
    }

    /**
     * @param object $result Model collected for request
     * @return array
     */
    protected function ToArray($result)
    {
        $model = [];
        $vars = $this->GetModelPublicProperties();
        foreach($vars as $var)
        {
            $name = $var->getName();
            $key = $this->Map($name);
            if(is_array($key) && count($key) === 2) { $key = $key[0]; }
            if(is_array($key) && count($key) === 1) { $key = $name; }
            if(in_array($key,[
                $this->CTS,
                $this->CBY,
                $this->MTS,
                $this->MBY,
                'CreatedAt',
                'ModifiedAt',
                'CreatedBy',
                'ModifiedBy'
            ]))
            {
                continue;
            }
            /*if(in_array($key,[
                $this->ID,
            ]))
            {
                $model[$this->ID] = ($this->AutoID)
                    ? $result->ID
                    : $result->{$name};
                continue;
            }*/
            if(property_exists($result,$name))
            {
                if($result->{$name} instanceof DateTime)
                {
                    $model[$key] = $result->{$name}->format('Y-m-d H:i:s');
                    continue;
                }
                if(is_null($result->{$name}) && !in_array($name,$this->NullFields))
                    continue;
                $model[$key] = $result->{$name};
            }
        }
        return $model;
    }

    /**
     * this function maps the parameters of the model to
     * the corresponding table fields.
     * @param string $key index to search by
     * or search values and return index
     * @param boolean $reverse
     * @return string
     */
    private function Map($key,$reverse=false)
    {
        if(!$reverse)
        {
            if(empty($this->Mapper)) return $key;
            $mapper = $this->Mapper;
            if($key === 'ID') return $this->ID;
            return (array_key_exists($key,$mapper))
                ? $mapper[$key] : $key;
        }
        if(empty($this->Mapper)) return $key;
        $mapper = $this->Mapper;
        if($key === $this->ID) return 'ID';
        foreach($mapper as $k => $val)
        {
            if(is_string($val) && $key === $val) return $k;
            if(is_array($val) && count($val) === 2)
                if($key === $val[0]) return $k;
        }
        return $key;
    }

    /**
     * @param array $result
     * @return object
     */
    protected function MapModel($result)
    {

        $model = $this->GetModel();
        $properties = $this->GetModelPublicProperties();


        foreach($properties as $val)
        {
            $name = $val->getName();
            $mapping = $this->Map($name);
            if(is_array($mapping))
            {
                if(count($mapping) === 2)
                {
                    $mappedName = $mapping[0];
                    $mappedType = $mapping[1];
                }
                elseif(count($mapping) === 1)
                {
                    $mappedName = $name;
                    $mappedType = $mapping[0];
                }
                else
                {
                    $mappedName = $name;
                    $mappedType = 'string';
                }
            }
            else
            {
                $mappedName = $mapping;
                $mappedType = 'string';
            }
            if(isset($this->Transformer[$name]))
            {
                $method = $this->Transformer[$name];
                if(is_callable($method))
                {
                    $model->{$name} = $method($result);
                }
                else
                {
                    $name = call_user_func($method,$result);
                    $model->{$name} = ($name) ? $name : null;
                }
            }
            else if(isset($result[$mappedName]))
                $model->{$name} = $this->MapType($result[$mappedName],$mappedType);

        }
        if($model instanceof AuditCM)
        {
            /**
             * @var AuditCM $model
             */
            try {
                $model->CreatedAt = new DateTime($result[$this->CTS]);
            } catch (Exception $e) {
            }
            try {
                $model->ModifiedAt = new DateTime($result[$this->MTS]);
            } catch (Exception $e) {
            }
            $model->CreatedBy = $result[$this->CBY];
            $model->ModifiedBy = $result[$this->MBY];
        }
        $model->ID = $result[$this->ID];
        if(is_numeric($model->ID))
            $model->ID = (int)$model->ID;
        return $model;
    }

    protected function MapType($elem,$type="string")
    {
        if(is_null($elem)) return null;
        $type = strtolower($type);
        switch($type)
        {
            case "string"       : { return (string)$elem;           }
            case "int"          : { return (int)$elem;              }
            case "double"       : { return (double)$elem;           }
            case "float"        : { return (float)$elem;            }
            case "integer"      : { return (integer)$elem;          }
            case "bool"         : { return (bool)$elem;             }
            case "boolean"      : { return (boolean)$elem;          }
            case "serialized"   : { return unserialize($elem);      }
            case "json"         : { return json_decode($elem,true); }
            case "datetime":
            case "date"         : {
                try {
                    return new DateTime($elem);
                } catch (Exception $e) {
                    return null;
                }
            }
        }
        return null;
    }

    /**
     * @param int|string|array $id unique identifier for the record
     * @param callable|string $func
     * @return null|object
     */
    protected function GetWithQuery($id,$func)
    {
        $column = (is_string($func)) ? $func : $func($this,$id);
        if(is_string($column))
        {
            $column = (!empty($column)) ? $column : $this->Table.'.'.$this->ID;
            $where = [$column => $id];
        }

        elseif(is_array($column))
        {
            $where = $column;
        }
        else
        {
            return null;
        }

        if($this->SoftDelete)
        {
            if(isset($where['AND']))
                $where["AND"][] = [$this->TableStatus.'[!=]' => Status::DELETED];
            else
                $where["AND"] = [$this->TableStatus.'[!=]' => Status::DELETED];
        }

        //$select_columns = $this->GetQueryColumns();
        $select_columns = "*";
        $res = (!empty($this->Join))
            ? $this->DBModel->select($this->Table,$this->Join,$select_columns,$where)
            : $this->DBModel->select($this->Table,$select_columns,$where);

        if(is_array($res) && !empty($res))
        {
            return $this->MapModel($res[0]);
        }
        //$this->Logger->addError($this->DBModel->error());
        return null;
    }

    /**
     * @param array $params parameters for function
     * @param callable $func search criteria
     * @return array|null
     */
    protected function GetAllWithQuery($params,$func)
    {
        $where = $func($this,$params);
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

        if($this->SoftDelete)
        {
            if(isset($where['AND']))
                $where["AND"][] = [$this->TableStatus.'[!=]' => Status::DELETED];
            else
                $where["AND"] = [$this->TableStatus.'[!=]' => Status::DELETED];
        }

        //$select_columns = $this->GetQueryColumns();
        $select_columns = "*";
        $res = (!empty($this->Join))
            ? $this->DBModel->select($this->Table,$this->Join,$select_columns,$where)
            : $this->DBModel->select($this->Table,$select_columns,$where);

        $result = [];
        if(is_array($res) && !empty($res))
        {
            foreach($res as $r)
                $result[] = $this->MapModel($r);
        }
        else
        {
            //$this->Logger->addError($this->DBModel->error());
        }
        return $result;
    }

    /**
     * @param array $params
     * @param callable $func
     * @return bool
     */
    protected function DeleteAllWithQuery($params,$func)
    {
        $res = $this->GetAllWithQuery($params,$func);
        if(is_array($res))
        {
            foreach($res as $item){ $this->Delete($item->ID); }
            return true;
        }
        return false;
    }

    /**
     * @param array $params
     * @param callable $func
     * @return bool
     */
    protected function HardDeleteAllWithQuery($params,$func)
    {
        $res = $this->GetAllWithQuery($params,$func);
        if(is_array($res))
        {
            foreach($res as $item) { $this->HardDelete($item->ID); }
            return true;
        }
        return false;
    }

    /**
     * @param object $model populated instance of the model
     * @return object|null
     */
    public function Add($model)
    {
        $model = $this->ToArray($model);
        //exclude parameters that will be handled outside of the loop
        $exclude = [
            $this->CTS,
            $this->MTS,
            $this->CBY,
            $this->MBY,
            $this->ID
        ];

        $data = [];
        foreach($model as $key => $val)
        {
            if(in_array($key,$exclude))
                continue;
            $data[$key] = $val;
        }
        if($this->Model instanceof AuditCM)
        {
            $data[$this->CTS] = date("Y-m-d H:i:s");
            $data[$this->MTS] = date("Y-m-d H:i:s");
            $data[$this->CBY] = $this->AppUser;
            $data[$this->MBY] = $this->AppUser;
        }
        if(!$this->AutoID && isset($model[$this->ID]))
            $data[$this->ID] = $model[$this->ID];
        /*if(is_null($model[$this->TableStatus]))
            $data[$this->TableStatus] = StatusCode::ACTIVE;*/
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
     * @param int|string $id
     * @return null|object
     */
    public function Get($id)
    {
        $where = [$this->Table.'.'.$this->ID => $id];
        //$select_columns = $this->GetQueryColumns();
        $select_columns = "*";
        $res = (!empty($this->Join))
            ? $this->DBModel->select($this->Table,$this->Join,$select_columns,$where)
            : $this->DBModel->select($this->Table,$select_columns,$where);

        if(is_array($res) && !empty($res))
        {
            return $this->MapModel($res[0]);
        }
        //$this->Logger->addError($this->DBModel->error());
        return null;
    }

    public function GetAll()
    {
        //$select_columns = $this->GetQueryColumns();
        $select_columns = "*";
        $res = (!empty($this->Join))
            ? $this->DBModel->select($this->Table,$this->Join,$select_columns)
            : $this->DBModel->select($this->Table,$select_columns);

        $result = [];
        if(is_array($res) && !empty($res))
        {
            foreach($res as $r)
                $result[] = $this->MapModel($r);
        }
        else
        {
            //$this->Logger->addError($this->DBModel->error());
        }
        return $result;
    }

    /**
     * @param object $model populated instance of the model with updated info
     * @return object|null
     */
    public function Update($model)
    {
        $model = $this->ToArray($model);
        //check if the item exists first
        $res = $this->Get($model[$this->ID]);
        if(!is_null($res))
        {
            //capture the original data for referencing
            $original = $res;
            $exclude = [
                $this->CTS,
                $this->MTS,
                $this->CBY,
                $this->MBY,
                $this->ID
            ];
            $data = [];
            foreach($model as $key => $val)
            {
                if(in_array($key,$exclude))
                    continue;

                //only add to update list if the value is different from the original
                //and the value is not null
                $k = $this->Map($key,true);
                $o = $original->{$k};
                if($o instanceof DateTime) $o = $o->format('Y-m-d H:i:s');
                if(
                    ($o !== $val) &&
                    (
                        (!is_null($val)) ||
                        (is_null($val) && in_array($k,$this->NullFields))
                    )
                )
                    $data[$key] = $val;
            }
            if($this->Model instanceof AuditCM)
            {
                $data[$this->MTS] = date("Y-m-d H:i:s");
                $data[$this->MBY] = $this->AppUser;
            }

            $res = $this->DBModel->update($this->Table,$data,[$this->ID => $model[$this->ID]]);
            $model = (!is_bool($res) && !is_null($res) && $res->rowCount() === 1)
                ? $this->Get($model[$this->ID])
                : null;
            //if(is_null($model)) //$this->Logger->addError($this->DBModel->error());
            return $model;
        }
        else
        {
            //$this->Logger->addError($this->DBModel->error());
            return null;
        }
    }

    /**
     * @param int $id unique identifier for the record
     * @return boolean
     */
    public function Delete($id)
    {
        if(!$this->SoftDelete)
            return $this->HardDelete($id);
        $model = $this->Get($id);
        if(!is_null($model))
        {
            $model->{$this->TableStatus} = Status::DELETED;
            $res = $this->Update($model);
            if(!is_null($res))
            {
                return true;
            }
            //$this->Logger->addError($this->DBModel->error());
            return false;
        }
        return false;
    }

    /**
     * @param int $id unique identifier for the record
     * @return bool
     */
    public function HardDelete($id)
    {
        $res = $this->DBModel->delete($this->Table,[$this->ID => $id]);
        if($res->rowCount() === 1)
        {
            return true;
        }
        //$this->Logger->addError($this->DBModel->error());
        return false;
    }

    /**
     * @param int $id unique identifier for the record
     * @return object|null
     */
    public function Restore($id)
    {
        $model = $this->Get($id);
        if(!is_null($model))
        {
            $model->{$this->TableStatus} = Status::LOCKED;
            return $this->Update($model);
        }
        return null;
    }

    /**
     * @inheritDoc
     */
    public function GetViewModel($model, $filter, $filterType = "out")
    {
        $viewModel = [];
        $vars = (new ReflectionObject($model))->getProperties(ReflectionProperty::IS_PUBLIC);
        foreach($vars as $var)
        {
            if(!is_null($filter))
            {
                switch(strtolower($filterType))
                {
                    case "out":{
                        if(in_array($var->getName(),$filter))
                            break;
                        $viewModel[$var->getName()] = $model->{$var->getName()};
                        break;
                    }
                    case "in":{
                        if(!in_array($var->getName(),$filter))
                            break;
                        $viewModel[$var->getName()] = $model->{$var->getName()};
                        break;
                    }
                    default:{
                        break;
                    }
                }
            }
            else
            {
                $viewModel[$var->getName()] = $model->{$var->getName()};
            }
        }
        return (object)$viewModel;
    }
}