<?php


namespace App\Controller;

use App\Constant\ContactNumberCode;
use App\Constant\GenderCode;
use App\Constant\MaritalStatusCode;
use App\Constant\TitleCode;
use App\Model\CountryCM;
use App\ViewModel\Def\SelectListModel;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;


class ListController extends BaseController
{
    public function Country(Request $rq, Response $rs)
    {
        $result = [];
        /**
         * @var CountryCM[] $lic
         */
        $lic = $this->Context->Country->GetAll();
        if(!empty($lic))
            foreach($lic as $l)
                $result[] = new SelectListModel($l->Name,$l->ID);

        return $rs->withJson($result);
    }

    public function States(Request $rq, Response $rs)
    {
        $result = [];
        $states = $this->Context->State->FindAllStatesByCountry($rq->getAttribute('id'));
        if(is_array($states))
            foreach($states as $state)
                $result[] = new SelectListModel($state->Name,$state->ID);

        return $rs->withJson($result);
    }

    public function Gender(Request $rq, Response $rs)
    {
        $result[] = new SelectListModel('Male',GenderCode::MALE);
        $result[] = new SelectListModel('Female',GenderCode::FEMALE);
        $result[] = new SelectListModel('Other',GenderCode::UNSPECIFIED);
        return $rs->withJson($result);
    }

    public function ContactNumber(Request $rq, Response $rs)
    {
        return $rs->withJson(ContactNumberCode::ToList());
    }

    public function Marital(Request $rq, Response $rs)
    {
        return $rs->withJson(MaritalStatusCode::ToList());
    }

    public function Title(Request $rq, Response $rs)
    {
        return $rs->withJson(TitleCode::ToList());
    }
}