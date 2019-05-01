<?php


namespace App\Model;


class AuditCM extends BaseCM
{
    /**
     * @var \DateTime
     */
    public $CreatedAt;

    /**
     * @var \DateTime
     */
    public $ModifiedAt;

    /**
     * @var string
     */
    public $CreatedBy;

    /**
     * @var string
     */
    public $ModifiedBy;
}