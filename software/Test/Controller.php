<?php


namespace Software\Test;


use App\Controller\BaseController;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class Controller extends BaseController
{
    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs)
    {
        $body = $rs->getBody();
        $body->write($this->Context->GiftContainer->GetItemCount(1));
        return $rs->withBody($body);
    }
}