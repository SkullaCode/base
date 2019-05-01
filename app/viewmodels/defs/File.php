<?php


namespace App\ViewModel\Def;

use App\Model\FileCM;
use App\Traits\ViewModelAutoMap;

class File
{
    use ViewModelAutoMap;

    /**
     * File constructor.
     * @param FileCM $model
     */
    public function __construct($model)
    {
        $this->AutoMap($model);
    }
    /**
     * @var string
     */
    public $Name;

    /**
     * @var string
     */
    public $Extension;

    /**
     * @var string
     */
    public $FileType;

    /**
     * @var int
     */
    public $Size;

    /**
     * @var string
     */
    public $Content;

}