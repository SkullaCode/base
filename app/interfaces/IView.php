<?php


namespace App\Interfaces;


interface IView
{
    /**
     * @param string $template
     * @param object $data
     * @return string
     */
    public function PHPHtml($template,$data);

    /**
     * @param string $template
     * @param object $data
     * @return string
     */
    public function TwigHtml($template,$data);

    /**
     * @param string $template
     * @param object $data
     * @return string
     */
    public function SmartyHtml($template,$data);
}