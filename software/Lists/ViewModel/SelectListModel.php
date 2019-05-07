<?php


namespace Software\Lists\ViewModel;


class SelectListModel
{
    /**
     * @var string represents the value of the entity
     */
    public $Value;

    /**
     * @var string represents the name of the entity
     */
    public $Text;

    public function __construct($text='',$val='')
    {
        $this->Value = $val;
        $this->Text = $text;
    }
}