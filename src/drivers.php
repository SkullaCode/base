<?php

use Delight\Auth\Auth;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use Medoo\Medoo;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use PHPMailer\PHPMailer\PHPMailer;
use Psr\Container\ContainerInterface;
use Slim\Psr7\Response;
use Slim\Views\PhpRenderer;
use Slim\Views\Twig;
use SlimSession\Helper;

/**
 * @var ContainerInterface $container
 */

$container->set('PHPMailer',function(ContainerInterface $c){
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
});

$container->set('Medoo',function(ContainerInterface $c){
    $environment = $c->get("settings")['config'];
    $settings = $environment[$environment['mode']];
    if(isset($settings['database'])) { $settings = $settings['database']; }
    return new Medoo($settings);
});

$container->set('FlySystem',function(ContainerInterface $c)
{
    $directory = $c->get('settings')['config']['storage_directory'];
    $adapter = new Local($directory);
    return new Filesystem($adapter);
});

$container->set('MonoLog',function(ContainerInterface $c){
    $path = $c->get('settings')['config']['root_directory'].DIRECTORY_SEPARATOR.'logs'.DIRECTORY_SEPARATOR.'logger';
    $settings = [
        'name'      =>  'default',
        'path'      =>  $path,
        'level'     =>  'error'
    ];
    $log = new Logger($settings['name']);
    $log->pushHandler(new StreamHandler($settings['path'],$settings['level']));
    return $log;
});

$container->set('Session',function(ContainerInterface $c){
    return new Helper();
});

$container->set('PHPRenderer',function (ContainerInterface $c) {
    $settings = $c->get('settings');
    return new PhpRenderer($settings['config']['template_path']);
});

$container->set('TwigRenderer',function(ContainerInterface $c){
    $settings = $c->get('settings');
    return new Twig($settings['config']['template_path']);
});

$container->set('SmartyRenderer',function(ContainerInterface $c){
    //todo get a smarty that can work with slim 4
    $settings = $c->get('settings');
    return new Twig($settings['config']['template_path']);
});

$container->set('Response',function(ContainerInterface $c){
    return new Response();
});

$container->set('Authenticator',function(ContainerInterface $c){
    $db = null;
    $db_settings = $c->get('settings');
    $db_settings = $db_settings['config'][$db_settings['config']['mode']]['database'];
    switch($db_settings['database_type'])
    {
        case 'sqlite':  {
            $db = new PDO('sqlite:'.$db_settings['database_file']);
            break;
        }
        case 'mysql': {
            $dsn = (
                'mysql:dbname='.
                $db_settings['database_name'].
                ';host='.
                $db_settings['server'].
                ';port='.
                $db_settings['port']
            );
            $db = new PDO($dsn,$db_settings['username'],$db_settings['password']);
            break;
        }
    }

    if(is_null($db)) trigger_error("A valid authentication driver type could not be determined");
    return new Auth($db);
});

$container->set("Version",function(ContainerInterface $c){
    $version = @file_get_contents(ROOT_FOLDER.'version.json');
    if($version !== false)
    {
        return json_decode($version);
    }
    return (object)[
        'app_version'           =>  "",
        'framework_version'     =>  "",
        'inherit'               =>  true
    ];
});