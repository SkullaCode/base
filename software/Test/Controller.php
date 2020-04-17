<?php


namespace Software\Test;


use App\Controller\BaseController;
use Psr\Http\Message\ServerRequestInterface;

class Controller extends BaseController
{
    public function __invoke(ServerRequestInterface $rq)
    {

    }
}