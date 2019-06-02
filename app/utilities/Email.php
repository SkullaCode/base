<?php


namespace App\Utility;

use App\Interfaces\IEmail;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use Psr\Container\ContainerInterface;

class Email implements IEmail
{
    /**
     * @var string
     */
    private $to;

    /**
     * @var string
     */
    private $toname;

    /**
     * @var string
     */
    private $message;

    /**
     * @var string
     */
    private $subject;

    /**
     * @var bool
     */
    private $email_sent;

    /**
     * @var string
     */
    private $debug_info;

    /**
     * @var PHPMailer
     */
    private $mailer;

    /**
     * @var PHPMailer
     */
    private $driver;

    /**
     * @var ArrayFunction
     */
    private $arrayFunction;

    /**
     * @var string
     */
    private $template_directory;

    /**
     * @var array
     */
    private $settings;


    public function __construct(ContainerInterface $c)
    {
        $this->driver = $c->get('PHPMailer');
        $this->mailer = clone $this->driver;
        $this->arrayFunction = $c->get('ArrayFunctionUtility');
        $this->subject = '';
        $this->message = '';
        $this->to = '';
        $this->toname = '';
        $this->email_sent = false;
        $this->template_directory = $c->get('settings')['email']['template-directory'];
        $this->settings = $c->get('settings')['company'];
    }

    public function Refresh()
    {
        $this->mailer = clone $this->driver;
        $this->subject = "";
        $this->message = "";
        $this->to = "";
        $this->toname = "";
        $this->email_sent = false;
    }

    public function AddAttachmentFile($filename,$extension,$name='FileAttachment')
    {
        try {
            $this->mailer->addAttachment($filename, $name . '.' . $extension);
        } catch (Exception $e) {
        }
    }

    public function AddAttachmentString($file,$extension,$name='FileAttachment')
    {
        $this->mailer->addStringAttachment($file,$name.'.'.$extension);
    }

    public function AddCC($email,$name='')
    {
        $this->mailer->addCC($email,$name);
    }

    public function AddSubject($message)
    {
        $this->subject = $message;
    }

    public function AddMessage($message)
    {
        $this->message = $message;
    }

    public function AddRecipient($email,$name="")
    {
        $this->to = $email;
        $this->toname = $name;
    }

    public function SendMail()
    {
        (!empty($this->toname))
            ? $this->mailer->addAddress($this->to,$this->toname)
            : $this->mailer->addAddress($this->to)
        ;
        $this->mailer->Debugoutput = 'html';
        $this->mailer->Subject = $this->subject;
        $this->mailer->Body = $this->message;
        $this->mailer->AltBody = $this->message;
        try {
            $this->email_sent = $this->mailer->send();
        } catch (Exception $e) {
        }
        $this->debug_info = $this->mailer->ErrorInfo;
        return $this->email_sent;
    }

    public function GetDebugInfo()
    {
        return (!is_null($this->debug_info)) ? $this->debug_info : '';
    }

    public function MailSent()
    {
        return $this->email_sent;
    }

    /**
     * @param $file
     * @param $data
     * @param bool $return
     * @return string|null
     */
    public function LoadTemplate($file,$data,$return=false)
    {
        $data = $this->arrayFunction->ObjectToArray($data);
        $path = $this->template_directory . $file . '.php';
        if(!file_exists($path))
        {
            return null;
        }
        $data['settings'] = $this->settings;
        ob_start();
        extract($data,EXTR_PREFIX_ALL,'emv');
        require_once $path;
        $buffer = ob_get_contents();
        @ob_end_clean();
        if($return) return $buffer;
        $this->message = $buffer;
        return null;
    }
}