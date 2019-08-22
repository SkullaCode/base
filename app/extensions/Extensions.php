<?php


namespace App\Extension;


use App\Constant\RequestModel;
use App\Utility\Logger;
use App\Utility\View;
use Exception;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class Extensions
{
    /**
     * @var ContainerInterface
     */
    private static $container;

    /**
     * @var View
     */
    private static $renderEngine;

    public static function init(ContainerInterface $c)
    {
        self::$container = $c;
        self::$renderEngine = $c->get('ViewUtility');
    }

    /**
     * @param ServerRequestInterface $rq
     * @return string
     */
    private static function RenderView($rq)
    {
        $renderEngineType = self::$container->get('settings');
        $renderEngineType = strtolower($renderEngineType['config']['render_engine']);
        $template = $rq->getAttribute(RequestModel::TEMPLATE);
        $params = $rq->getAttribute(RequestModel::PROCESSED_MODEL);
        $buffer = "";
        switch($renderEngineType)
        {
            case 'php'      : { $buffer = self::$renderEngine->PHPHtml($template,$params);     break; }
            case 'twig'     : { $buffer = self::$renderEngine->TwigHtml($template,$params);    break; }
            case 'smarty'   : { $buffer = self::$renderEngine->SmartyHtml($template,$params);  break; }
        }
        return $buffer;
    }

    /**
     * @param Request $rq
     * @param Response $rs
     * @param int $code
     * @param string $message
     * @return Response
     */
    public static function ErrorHandler(Request $rq, Response $rs,$code=500,$message="Application Error")
    {
        if(is_string($code)){ $message = $code; $code = 500; }
        $response = clone $rs;
        $error = [
            'Location'      =>  $rq->getAttribute("Error_Location"),
            'Entity'        =>  $rq->getAttribute("Error_Entity"),
            'Code'          =>  $rq->getAttribute("Error_Code")
        ];
        if(empty($error['Code']) || is_null($error['Code']))
            $error['Code'] = $message;
        $body = $response->getBody();
        //if($rq->getHeaderLine('X-Requested-With') === 'XMLHttpRequest')
        if(strtolower($rq->getHeaderLine('X-Requested-With')) === 'xmlhttprequest')
        {
            $info = json_encode($error,JSON_PRETTY_PRINT);
            $body->write($info);
            return $response
                ->withStatus($code,$message)
                ->withHeader("content-type","application/json")
                ->withHeader("content-length",strlen($info))
                ->withBody($body);
        }
        else
        {
            $rq = $rq
                ->withAttribute(RequestModel::TEMPLATE,"error.{$code}")
                ->withAttribute(RequestModel::PROCESSED_MODEL,(object)$error);
            $buffer = self::RenderView($rq);
            $body->write($buffer);
            return $response
                ->withStatus($code,$message)
                ->withHeader('content-type','text/html')
                ->withHeader('content-length',strlen($buffer))
                ->withBody($body);
        }
    }

    /**
     * @param Request $rq
     * @param Response $rs
     * @param int|string $code
     * @param string $message
     * @return Response
     */
    public static function SuccessHandler(Request $rq, Response $rs,$code=200,$message="Request Processed Successfully")
    {
        if(is_string($code)){ $message = $code; $code = 200; }
        $response = clone $rs;
        $body = $response->getBody();
        if($rq->getHeaderLine('X-Requested-With') === 'XMLHttpRequest')
        {
            $info = $rq->getAttribute(RequestModel::PROCESSED_MODEL);
            $info = (!is_null($info))
                ? json_encode($info,JSON_PRETTY_PRINT)
                : "";
            $body->write($info);
            return $response
                ->withStatus($code,$message)
                ->withHeader("content-type","application/json")
                ->withHeader("content-length",strlen($info))
                ->withBody($body);
        }
        else
        {
            $buffer = self::RenderView($rq);
            $body->write($buffer);
            return $response
                ->withStatus($code,$message)
                ->withHeader('content-type','text/html')
                ->withHeader('content-length',strlen($buffer))
                ->withBody($body);
        }
    }

    public static function LogHandler(Exception $e)
    {
        /**
         * @var Logger $logger
         */
        $logger = self::$container->get('LoggerUtility');
        $logger->AddMessage($e->getMessage());
        $logger->AddContent([
            'file'      =>  $e->getFile(),
            'code'      =>  $e->getCode(),
            'line'      =>  $e->getLine()
        ]);
        $logger->SaveLog();
    }
}