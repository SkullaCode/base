<?php

use League\Flysystem\Adapter\Local;
use Medoo\Medoo;
use PHPMailer\PHPMailer\PHPMailer;
use Psr\Container\ContainerInterface;


$container['PHPMailer'] = function(ContainerInterface $c){
    $s = $c->get('settings')['email'];
    $mailer = new PHPMailer();
    ($s['mail-type'] == 'html')
        ? $mailer->isHTML(true)
        : $mailer->isHTML(false);

    switch($s['protocol'])
    {
        case 'local':
        {
            $mailer->Host = $s['host'];
            $mailer->Port = (int)$s['port'];
            break;
        }
        case 'smtp':
        {
            $mailer->isSMTP();
            $mailer->Host = $s['host'];
            $mailer->Username = $s['username'];
            $mailer->Password = $s['password'];
            $mailer->Port = (int)$s['port'];
            if(!empty($s['secure-with']))
            {
                $mailer->SMTPAuth = true;
                $mailer->SMTPSecure = $s['secure-with'];
            }
            break;
        }
        case 'sendmail':
        {
            $mailer->isSendmail();
            $mailer->Host = $s['host'];
            $mailer->Port = (int)$s['port'];
            break;
        }
    }
    $mailer->setFrom($s['from'],$s['from-name']);
    return $mailer;
};

$container['Medoo'] = function(ContainerInterface $c){
    $environment = $c->get("settings")['config'];
    $settings = $environment[$environment['mode']];
    if(isset($settings['database'])) { $settings = $settings['database']; }
    return new Medoo($settings);
};

$container['FlySystem'] = function(ContainerInterface $c)
{
    $directory = $c->get('settings')['config']['storage_directory'];
    $adapter = new Local($directory);
    return new \League\Flysystem\Filesystem($adapter);
};

$container['MonoLog'] = function(ContainerInterface $c){
    $path = $c->get('settings')['config']['root_directory'].DIRECTORY_SEPARATOR.'logs'.DIRECTORY_SEPARATOR.'logger';
    $settings = [
        'name'      =>  'default',
        'path'      =>  $path,
        'level'     =>  'error'
    ];
    $log = new \Monolog\Logger($settings['name']);
    $log->pushHandler(new \Monolog\Handler\StreamHandler($settings['path'],$settings['level']));
    return $log;
};

$container['SlimSession'] = function(ContainerInterface $c){
    return new \SlimSession\Helper();
};

/*$container['errorHandler'] = function(ContainerInterface $c){
    return function(\Slim\Http\Request $rq, \Slim\Http\Response $rs, Exception $exception) use ($c){
        $rq = $rq->withAttribute("Error_Location",$exception->getFile()." Line:".$exception->getLine());
        return \App\Extension\Extensions::ErrorHandler($rq,$rs,500,$exception->getMessage());
    };
};

$container['notFoundHandler'] = function(ContainerInterface $c){
    return function(\Slim\Http\Request $rq, \Slim\Http\Response $rs) use ($c){
        return \App\Extension\Extensions::ErrorHandler($rq,$rs,404,"Specified route not found");
    };
};*/
