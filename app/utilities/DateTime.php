<?php


namespace App\Utility;


class DateTime
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
        return 'l F d, Y';
    }

    /**
     * @param \DateTime $date DateTime object
     * @return string
     */
    public function FormatDateToString($date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    /**
     * @param string $stringDate string representation of date in (Y-m-d H:i:s)
     * @return \DateTime
     */
    public function FormatStringToDate($stringDate)
    {
        return new \DateTime($stringDate);
    }

    /**
     * @param int $timeStamp unix time stamp value
     * @return string
     */
    public function FormatTimeStampToString($timeStamp)
    {
        return (new \DateTime())->setTimestamp($timeStamp)->format($this->DateTimeStringFormat());
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
        return $date->format(self::DateTimeStringFormatUI());
    }
}