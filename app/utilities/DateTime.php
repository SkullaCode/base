<?php


namespace App\Utility;


use App\Interfaces\IDateTime;
use Exception;

class DateTime implements IDateTime
{
    /**
     * @return string
     */
    public function DateTimeStringFormat()
    {
        return 'Y-m-d H:i:s';
    }

    /**
     * @return string
     */
    public function DateStringFormat()
    {
        return 'Y-m-d';
    }

    public function DateStringFormatUI()
    {
        return 'l F d, Y';
    }

    /**
     * @return string
     */
    public function TimeStringFormat12()
    {
        return 'h:i:s A';
    }

    /**
     * @return string
     */
    public function TimeStringFormat24()
    {
        return 'H:i:s';
    }

    /**
     * @return string
     */
    public function DateTimeStringFormatUI()
    {
        return 'l F d, Y \a\t h:i:s A';
    }

    /**
     * @param \DateTime $date DateTime object
     * @param null|string $string format
     * @return string
     */
    public function FormatDateToString($date, $string=null)
    {
        if(is_null($string)) return $date->format($this->DateStringFormat());
        return $date->format($string);
    }

    /**
     * @param \DateTime $date DateTime object
     * @param null|string $string format
     * @return string
     */
    public function FormatDateTimeToString($date, $string = null)
    {
        if(is_null($string)) return $date->format($this->DateTimeStringFormat());
        return $date->format($string);
    }

    /**
     * @param string $stringDate string representation of date in (Y-m-d H:i:s)
     * @return \DateTime
     * @throws Exception
     */
    public function FormatStringToDate($stringDate)
    {
        return new \DateTime($stringDate);
    }

    /**
     * @param int $timeStamp unix time stamp value
     * @param string $string format
     * @return string
     * @throws Exception
     */
    public function FormatTimeStampToString($timeStamp, $string)
    {
        return (new \DateTime())->setTimestamp($timeStamp)->format($string);
    }

    /**
     * @param string $stringDate string representation of date in (Y-m-d H:i:s)
     * @return int
     */
    public function FormatStringToTimeStamp($stringDate)
    {
        $date = \DateTime::createFromFormat($this->DateTimeStringFormat(),$stringDate);
        return $date->getTimestamp();
    }

    /**
     * @param int $timeStamp unix time stamp value
     * @return \DateTime
     * @throws Exception
     */
    public function FormatTimeStampToDate($timeStamp)
    {
        return (new \DateTime())->setTimestamp($timeStamp);
    }

    /**
     * @param \DateTime $date DateTime object
     * @return int
     */
    public function FormatDateToTimeStamp($date)
    {
        return $date->getTimestamp();
    }

    /**
     * @param \DateTime $date object
     * @return string
     */
    public function FormatDateToUIString($date)
    {
        return $date->format($this->DateStringFormatUI());
    }

    /**
     * @param \DateTime $date object
     * @return string
     */
    public function FormatDateTimeToUIString($date)
    {
        return $date->format($this->DateTimeStringFormatUI());
    }
}