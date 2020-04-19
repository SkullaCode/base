<?php


namespace Software\Lists;


use App\Constant\RequestModel;
use App\Controller\BaseController;
use App\Extension\Extensions;
use Psr\Http\Message\ServerRequestInterface;
use Software\Entity\User\Constants\PrivilegeCode;
use Software\Lists\Constant\ContactNumberCode;
use Software\Lists\Constant\GenderCode;
use Software\Lists\Constant\MaritalStatusCode;
use Software\Lists\Constant\TitleCode;

class Service extends BaseController
{
    public function ContactNumber(ServerRequestInterface $rq)
    {
        $rq = $rq->withAttribute(RequestModel::PROCESSED_MODEL,ContactNumberCode::ToList());
        return Extensions::SuccessHandler($rq);
    }

    public function Gender(ServerRequestInterface $rq)
    {
        $result[] = new Model('Male',GenderCode::MALE);
        $result[] = new Model('Female',GenderCode::FEMALE);
        $result[] = new Model('Other',GenderCode::UNSPECIFIED);
        $rq = $rq->withAttribute(RequestModel::PROCESSED_MODEL,$result);
        return Extensions::SuccessHandler($rq);
    }

    public function Marital(ServerRequestInterface $rq)
    {
        $rq = $rq->withAttribute(RequestModel::PROCESSED_MODEL,MaritalStatusCode::ToList());
        return Extensions::SuccessHandler($rq);
    }

    public function Title(ServerRequestInterface $rq)
    {
        $rq = $rq->withAttribute(RequestModel::PROCESSED_MODEL,TitleCode::ToList());
        return Extensions::SuccessHandler($rq);
    }

    public function Role(ServerRequestInterface $rq)
    {
        $rq = $rq->withAttribute(RequestModel::PROCESSED_MODEL,PrivilegeCode::ToList());
        return Extensions::SuccessHandler($rq);
    }
}