<?php


namespace App\Interfaces;


interface IEmail
{
    public function Refresh();

    public function AddAttachmentFile($filename,$extension,$name='FileAttachment');

    public function AddAttachmentString($file,$extension,$name='FileAttachment');

    public function AddCC($email,$name='');

    public function AddSubject($message);

    public function AddMessage($message);

    public function AddRecipient($email,$name="");

    public function SendMail();

    public function GetDebugInfo();

    public function MailSent();

    public function LoadTemplate($file,$data,$return=false);
}