<?php


namespace App\Model;


class SessionCM
{
    /**
     * @var string unique key used for associating the user with an internal session
     * the token is passed on each request
     */
    public $Token;

    /**
     * @var string unique key used to identify the user that owns the session. if further
     * information is required on the user this key will allow that information to be retrieved.
     */
    public $UID;

    /**
     * @var string Logged in user's email address
     */
    public $EmailAddress;

    /**
     * @var string Logged in user's first name
     */
    public $FirstName;

    /**
     * @var string Logged in user's last name
     */
    public $LastName;

    /**
     * @var string IP address from which the connection is established
     */
    public $IPAddress;

    /**
     * @var string user agent information from the computer
     * the connection is established
     */
    public $UserAgent;

    /**
     * @var \DateTime Timestamp at which the connection was established
     */
    public $LoggedInAt;

    /**
     * @var \DateTime Timestamp at which the connection should be terminated
     */
    public $Expires;
}