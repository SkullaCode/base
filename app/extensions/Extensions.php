<?php


namespace App\Extension;


use App\Utility\Logger;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class Extensions
{
    /**
     * @var ContainerInterface
     */
    private static $container;

    public static function init(ContainerInterface $c)
    {
        self::$container = $c;
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
        if($rq->getHeaderLine('X-Requested-With') === 'XMLHttpRequest')
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
            $dir = self::$container->get('settings')['environment']['error_page_directory'];
            $file = $dir.$code.'.php';
            $buffer = "";
            if(file_exists($file))
            {
                ob_start();
                require_once $file;
                $buffer = ob_get_contents();
                @ob_end_clean();
            }
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
     * @param int $code
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
            $info = json_encode($rq->getAttribute("ProcessedViewModel"),JSON_PRETTY_PRINT);
            $body->write($info);
            return $response
                ->withStatus($code,$message)
                ->withHeader("content-type","application/json")
                ->withHeader("content-length",strlen($info))
                ->withBody($body);
        }
        else
        {
            $buffer = "Request successful............";
            $body->write($buffer);
            return $response
                ->withStatus($code,$message)
                ->withHeader('content-type','text/html')
                ->withHeader('content-length',strlen($buffer))
                ->withBody($body);
        }
    }

    public static function LogHandler(\Exception $e)
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