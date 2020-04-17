<?php


namespace App\Interfaces;


interface IDateTime
{
    public function DateTimeStringFormat();

    public function DateTimeStringFormatUI();

    public function DateStringFormat();

    public function DateStringFormatUI();

    public function TimeStringFormat12();

    public function TimeStringFormat24();

    public function FormatDateToString($date, $string=null);

    public function FormatDateTimeToString($date, $string=null);

    public function FormatStringToDate($stringDate);

    public function FormatTimeStampToString($timeStamp, $string);

    public function FormatStringToTimeStamp($stringDate);

    public function FormatTimeStampToDate($timeStamp);

    public function FormatDateToTimeStamp($date);

    public function FormatDateToUIString($date);

    public function FormatDateTimeToUIString($date);
}