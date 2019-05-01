<?php

namespace App\Controller;


use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;


class TestController extends BaseController
{
    public function __invoke(Request $rq, Response $rs, $args)
    {

    }
}