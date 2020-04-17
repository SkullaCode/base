<?php


namespace App\Utility;

use App\Interfaces\ISession;
use Psr\Container\ContainerInterface;
use SlimSession\Helper;

class Session implements ISession
{
    /**
     * @var array
     */
    private $Storage;

    /**
     * @var array
     */
    private $Flash;

    /**
     * @var bool
     */
    private $Lock;

    /**
     * @var Helper
     */
    private $Driver;

    public function __construct(ContainerInterface $c)
    {
        if($c->has("Session"))
        {
            $this->Driver = $c->get("Session");
            $this->Lock = false;
            $this->Flash = $this->Driver->get('_flash',[]);
            $this->Driver->delete('_flash');
        }
        else
        {
            $this->Driver = null;
            $this->Lock = false;
            $this->Flash = [];
            if(isset($_SESSION['_flash']))
            {
                $this->Flash = $_SESSION['_flash'];
                unset($_SESSION['_flash']);
            }
            $this->Storage = $_SESSION;
        }
    }

    public function GetItem($id,$default=null)
    {
        if($this->Lock) return null;
        if(!is_null($this->Driver))
        {
            return $this->Driver->get($id,$default);
        }
        return (isset($this->Storage[$id]))
            ? $this->Storage[$id]
            : $default;
    }

    public function GetFlashItem($id,$default=null)
    {
        if($this->Lock) return null;
        return (isset($this->Flash[$id]))
            ? $this->Flash[$id]
            : $default;
    }

    public function SetItem($id,$value)
    {
        if(!$this->Lock)
        {
            (!is_null($this->Driver))
                ? $this->Driver->set($id,$value)
                : $this->Storage[$id] = $value;
        }
    }

    public function SetFlashItem($id,$value)
    {
        if(!$this->Lock)
            $this->Storage['_flash'][$id] = $value;
    }

    public function DeleteItem($id)
    {
        if(!$this->Lock)
        {
            if(!is_null($this->Driver))
            {
                $this->Driver->delete($id);
            }
            else
            {
                if(isset($this->Storage[$id]))
                    unset($this->Storage[$id]);
            }
        }
    }

    public function Lock()
    {
        if(!$this->Lock)
        {
            if(!is_null($this->Driver))
            {
                if(isset($this->Storage['_flash']))
                    $this->Driver->set("_flash",$this->Storage['_flash']);
            }
            else
            {
                $_SESSION = $this->Storage;
                session_write_close();
            }
            $this->Lock = true;
        }
    }

    public function Destroy()
    {
        $this->Storage = [];
        if(!is_null($this->Driver))
        {
            $x = $this->Driver;
            $x::destroy();
        }
        else
        {
            $_SESSION = null;
            session_unset();
            session_destroy();
            session_write_close();
        }
    }
}