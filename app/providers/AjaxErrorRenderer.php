<?php


namespace App\Provider;


use Slim\Interfaces\ErrorRendererInterface;
use Throwable;

class AjaxErrorRenderer implements ErrorRendererInterface
{

    /**
     * @inheritDoc
     */
    public function __invoke(Throwable $exception, bool $displayErrorDetails): string
    {
        //todo clean up
        $model = [
            'Error_Location'    =>  'App',
            'Error_Entity'      =>  'NA',
            'Error_Code'        =>  $exception->getCode(),
            'Error_Line'        =>  $exception->getLine(),
            'Error_Message'     =>  $exception->getMessage(),
            'Error_File'        =>  $exception->getFile()
        ];
        if($displayErrorDetails)
        {
            $model['Stack_Trace'] = $exception->getTrace();
        }
        return json_encode($model,JSON_PRETTY_PRINT);
    }
}