<?php


namespace App\Controller;

use App\Constant\RequestModel;
use App\Extension\Extensions;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;


class AppController extends BaseController
{
    private $version_information;

    public function __construct(ContainerInterface $c)
    {
        parent::__construct($c);
        $this->version_information = $c->get("Version");
    }

    public function Update(Request $rq)
    {
        return Extensions::SuccessHandler($rq,"Update started successfully");
    }

    public function Bootstrap(Request $rq)
    {
        return Extensions::SuccessHandler($rq,"Bootstrap completed");
    }

    public function Extract(Request $rq)
    {
        return Extensions::SuccessHandler($rq,"Extraction completed");
    }

    public function Backup(Request $rq)
    {
        return Extensions::SuccessHandler($rq,"Backup completed");
    }

    public function Execute(Request $rq)
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

    public function Cleanup(Request $rq)
    {
        $vm = $rq->getAttribute(RequestModel::VIEW_MODEL);
        $exec = $vm->Executor;
        if(!empty($exec['project_version']))
            $this->version_information->app_version = $exec['project_version'];
        if(!empty($exec['framework_version']))
            $this->version_information->framework_version = $exec['framework_version'];
        $data = json_encode($this->version_information,JSON_PRETTY_PRINT);
        @file_put_contents(ROOT_FOLDER.'version.json',$data);
        $session = $this->Utility->Session;
        $file = $this->Utility->FileSystem;
        $file->CleanDir($vm->WorkArea);
        $file->DeleteDir($vm->WorkArea);
        $session->DeleteItem("WorkArea");
        $session->DeleteItem("UpdateSource");
        $session->DeleteItem("UpdateArchive");
        return Extensions::SuccessHandler($rq,"Cleanup complete");
    }
}