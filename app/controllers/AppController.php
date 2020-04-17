<?php


namespace App\Controller;

use App\Constant\RequestModel;
use App\Extension\Extensions;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;


class AppController extends BaseController
{
    public function __construct(ContainerInterface $c)
    {
        parent::__construct($c);
    }

    public function Update(Request $rq)
    {
        $vm = $rq->getAttribute(RequestModel::VIEW_MODEL);
        $exec = $vm->Executor;
        $root_dir = ltrim($this->Environment->RootDirectory,DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR;
        if(!empty($exec['added']))
            foreach($exec['added'] as $file)
                $this->Utility->FileSystem->RecurseCopyDir(
                    $vm->UpdateSource.$file,
                    $root_dir.$file
                );

        if(!empty($exec['modified']))
            foreach($exec['modified'] as $file)
                $this->Utility->FileSystem->RecurseCopyDir(
                    $vm->UpdateSource.$file,
                    $root_dir.$file
                );

        if(!empty($exec['deleted']))
            foreach($exec['deleted'] as $file)
                @unlink($root_dir.$file);

        $this->FireHook('UpdateHook',$rq);

        return Extensions::SuccessHandler($rq,"Update executed successfully");
    }
}