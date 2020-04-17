<?php


namespace Software\EntityContext;


use App\DBContext\BaseEntityContext;
use Psr\Container\ContainerInterface;

class File extends BaseEntityContext
{
    public function __construct(ContainerInterface $c)
    {
        parent::__construct("files", $c, [

        ]);
        $this->ID = "ID";
        $this->AutoID = false;
        $this->HandleID = true;
    }

    public function UpdateFile($model)
    {
        return parent::Update($model);
    }
}