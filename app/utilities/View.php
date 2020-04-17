<?php


namespace App\Utility;


use App\Interfaces\IView;
use Psr\Container\ContainerInterface;
use Slim\Views\PhpRenderer;
//use Slim\Views\Smarty;
use Slim\Views\Twig;
use Twig\Error\LoaderError;

class View implements IView
{
    /**
     * @var PhpRenderer
     */
    private $phpDriver;

    /**
     * @var Twig
     */
    private $twigDriver;

    /**
     * @var Smarty
     */
    //private $smartyDriver;

    /**
     * @var ArrayFunction
     */
    private $arrayConverter;

    public function __construct(ContainerInterface $c)
    {
        $this->phpDriver = $c->get('PHPRenderer');
        $this->twigDriver = $c->get('TwigRenderer');
        //$this->smartyDriver = $c->get('SmartyRenderer');
        $this->arrayConverter = $c->get('ArrayFunctionUtility');
    }

    /**
     * @param string $template
     * @param object $data
     * @return string
     */
    public function PHPHtml($template,$data)
    {
        $attributes = $this->arrayConverter->ObjectToArray($data);
        $location = $this->ExtractFileName($template,'phtml');
        return $this->phpDriver->fetch($location,$attributes);
    }

    /**
     * @param string $template
     * @param object $data
     * @return string
     */
    public function TwigHtml($template,$data)
    {
        $attributes = $this->arrayConverter->ObjectToArray($data);
        $location = $this->ExtractFileName($template,'twig');
        try {
            return $this->twigDriver->fetch($location, $attributes);
        } catch (LoaderError $e) {
            return "";
        }
    }

    /**
     * @param string $template
     * @param object $data
     * @return string
     */
    public function SmartyHtml($template,$data)
    {
        $attributes = $this->arrayConverter->ObjectToArray($data);
        $location = $this->ExtractFileName($template,'tpl');
        //return $this->smartyDriver->fetch($location,$attributes);
    }

    private function ExtractFileName($file,$extension)
    {
        $parts = explode('.',$file);
        $file = implode(DIRECTORY_SEPARATOR,$parts);
        $file = trim($file,DIRECTORY_SEPARATOR);
        $file = "{$file}.{$extension}";
        return $file;
    }
}