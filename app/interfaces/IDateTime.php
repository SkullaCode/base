<?php


namespace App\Interfaces;


interface IDateTime
{
    public function DateTimeStringFormat();

    public function DateStringFormat();

    public function TimeStringFormat12();

    public function TimeStringFormat24();

    public function DateTimeStringFormatUI();

    public function FormatDateToString($date);

    public function FormatStringToDate($stringDate);

    public function FormatTimeStampToString($timeStamp);

    public function FormatStringToTimeStamp($stringDate);

    public function FormatTimeStampToDate($timeStamp);

    public function FormatDateToTimeStamp($date);

    public function FormatDateToUIString($date);
}