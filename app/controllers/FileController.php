<?php


namespace App\Controller;

use App\Model\FileCM;
use App\ViewModel\Def\File;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class FileController extends BaseController
{
    public function __invoke(Request $rq, Response $rs)
    {
        /**
         * @var File $file
         */
        $file = $this->Context->File->Get($rq->getAttribute('id'));
        if(is_null($file))
        {
           $fileModel = new FileCM();
           $fileModel->Name = "";
           $file->FileType = "image/gif";
           $fileModel->Content = "GIF89a\x01\x00\x01\x00\x80\x00\x00\xFF\xFF".
               "\xFF\x00\x00\x00\x21\xF9\x04\x01\x00\x00\x00\x00".
               "\x2C\x00\x00\x00\x00\x01\x00\x01\x00\x00\x02\x02".
               "\x44\x01\x00\x3B";
           $file = new File($fileModel);
        }
        $body = $rs->getBody();
        $body->write($file->Content);
        $rs = $rs
            ->withBody($body)
            ->withHeader("Content-type",$file->FileType)
            ->withAddedHeader("Content-length",strlen($file->Content));
        return $rs;
    }
}