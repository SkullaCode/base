<?php


namespace Software\Lists;


use App\Controller\BaseController;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Software\Lists\Constant\ContactNumberCode;
use Software\Lists\Constant\GenderCode;
use Software\Lists\Constant\MaritalStatusCode;
use Software\Lists\Constant\TitleCode;
use Software\Lists\ViewModel\SelectListModel;

class Service extends BaseController
{
    public function ContactNumber(ServerRequestInterface $rq, ResponseInterface $rs)
    {
        $body = $rs->getBody();
        if($body->isWritable())
            $body->write(json_encode(ContactNumberCode::ToList(),JSON_PRETTY_PRINT));
        $rs = $rs->withBody($body);
        return $rs;
    }

    public function Gender(ServerRequestInterface $rq, ResponseInterface $rs)
    {
        $result[] = new SelectListModel('Male',GenderCode::MALE);
        $result[] = new SelectListModel('Female',GenderCode::FEMALE);
        $result[] = new SelectListModel('Other',GenderCode::UNSPECIFIED);
        $body = $rs->getBody();
        if($body->isWritable())
            $body->write(json_encode($result,JSON_PRETTY_PRINT));
        $rs = $rs->withBody($body);
        return $rs;
    }

    public function Marital(ServerRequestInterface $rq, ResponseInterface $rs)
    {
        $body = $rs->getBody();
        if($body->isWritable())
            $body->write(json_encode(MaritalStatusCode::ToList(),JSON_PRETTY_PRINT));
        $rs = $rs->withBody($body);
        return $rs;
    }

    public function Title(ServerRequestInterface $rq, ResponseInterface $rs)
    {
        $body = $rs->getBody();
        if($body->isWritable())
            $body->write(json_encode(TitleCode::ToList(),JSON_PRETTY_PRINT));
        $rs = $rs->withBody($body);
        return $rs;
    }
}