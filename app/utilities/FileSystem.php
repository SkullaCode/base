<?php


namespace App\Utility;


use App\Interfaces\IFlySystem;
use League\Flysystem\FileExistsException;
use League\Flysystem\FileNotFoundException;
use League\Flysystem\RootViolationException;
use Psr\Container\ContainerInterface;

class FileSystem
{
    /**
     * @var IFlySystem file system driver
     */
    private $Driver;


    public function __construct(ContainerInterface $c)
    {
        $this->Driver = $c->get("FlySystem");
    }

    public function Write($fileName,$content)
    {
        try
        {
            if($this->Driver->write($fileName,$content))
            {
                $meta = $this->Driver->getMetadata($fileName);
                $meta['mime'] = $this->Driver->getMimetype($fileName);
                return ['meta'=>$meta,'content'=>$content];
            }
            return false;
        }
        catch(FileExistsException $e)
        {
            return false;
        }
        catch(FileNotFoundException $e)
        {
            return false;
        }
    }

    public function OverWrite($fileName,$content)
    {
        try
        {
            if($this->Driver->put($fileName,$content))
            {
                $meta = $this->Driver->getMetadata($fileName);
                $meta['mime'] = $this->Driver->getMimetype($fileName);
                return ['meta'=>$meta,'content'=>$content];
            }
            return false;
        }
        catch(FileNotFoundException $e)
        {
            return false;
        }
    }

    public function Read($fileName)
    {
        try
        {
            $meta = $this->Driver->getMetadata($fileName);
            $meta['mime'] = $this->Driver->getMimetype($fileName);
            return [
                'meta'      =>  $meta,
                'content'  =>  $this->Driver->read($fileName)
            ];
        }
        catch(FileNotFoundException $e)
        {
            return false;
        }
    }

    public function Meta($fileName)
    {
        try
        {
            $meta = $this->Driver->getMetadata($fileName);
            $meta['mime'] = $this->Driver->getMimetype($fileName);
            return $meta;
        }
        catch(FileNotFoundException $e)
        {
            return false;
        }
    }

    public function Delete($fileName)
    {
        try
        {
            return $this->Driver->delete($fileName);
        }
        catch(FileNotFoundException $e)
        {
            return false;
        }
    }

    public function Purge()
    {
        try
        {
            return $this->Driver->deleteDir("");
        }
        catch(RootViolationException $e)
        {
            return false;
        }
    }
}