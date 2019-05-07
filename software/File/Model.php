<?php


namespace Software\File;


use App\Model\AuditCM;

class Model extends AuditCM
{
    /**
     * @var string name of the file
     */
    public $Name;

    /**
     * @var string extension eg. png, jpg, etc
     */
    public $Extension;

    /**
     * @var string type of file eg. image/jpg, application/json etc...
     */
    public $MIME;

    /**
     * @var string file contents as encoded string
     */
    public $Content;

    /**
     * @var int size of the file in bytes (number of bytes)
     */
    public $Size;
}