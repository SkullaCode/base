<?php


namespace App\DBContext;

use App\Model\FileCM;
use Psr\Container\ContainerInterface;

class File extends BaseDbContext
{
    public function __construct(ContainerInterface $c)
    {
        parent::__construct(new FileCM(),$c);
        $this->Table = 'files';
        $this->AutoID = false;
        $this->TypeMapper = [
            'Size'          =>  'int'
        ];
    }
}