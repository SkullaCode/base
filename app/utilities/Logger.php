<?php


namespace App\Utility;


use Psr\Container\ContainerInterface;

class Logger
{
    /**
     * @var \Monolog\Logger
     */
    protected $driver;

    /**
     * @var string
     */
    protected $subject;

    /**
     * @var string
     */
    protected $message;

    /**
     * @var array
     */
    protected $content;

    /**
     * @var string[]
     */
    protected $recipient;

    /**
     * @var string;
     */
    protected $debugInfo;


    /**
     * Logger constructor.
     * @param ContainerInterface $c
     */
    public function __construct(ContainerInterface $c)
    {
        $this->driver = ($c->has('MonoLog')) ? $c->get('MonoLog') : null;
        $this->subject = '';
        $this->message = '';
        $this->content = [];
        $this->recipient = [];
        $this->debugInfo = '';
    }

    /**
     * @param string $subject
     */
    public function AddSubject($subject)
    {
        $this->subject = $subject;
    }

    /**
     * @param string $message
     */
    public function AddMessage($message)
    {
        $this->message = $message;
    }

    /**
     * @param string $email
     */
    public function AddRecipient($email)
    {
        $this->recipient[] = $email;
    }

    /**
     * @param array $content
     */
    public function AddContent($content)
    {
        $this->content = $content;
    }

    public function SaveLog()
    {
        $res = $this->driver->log(\Monolog\Logger::ERROR,$this->message,$this->content);
    }

    public function GetDebugInfo()
    {
        return $this->debugInfo;
    }

    public function Refresh()
    {
        $this->subject = '';
        $this->message = '';
        $this->recipient = [];
        $this->content = [];
        $this->debugInfo = '';
    }
}