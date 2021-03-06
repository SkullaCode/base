<?php


namespace App\Utility;


use App\Interfaces\IRequest;

class Request implements IRequest
{
    /**
     * @return string
     */
    public function UserAgent()
    {
        return (isset($_SERVER['HTTP_USER_AGENT'])) ? $_SERVER['HTTP_USER_AGENT'] : 'Unknown';
    }

    /**
     * @return string
     */
    public function HostName()
    {
        return $_SERVER['HTTP_HOST'];
    }

    /**
     * @param string $slug
     * @return string
     */
    public function BaseURL($slug = "")
    {
        $slug = ltrim($slug,"/");
        $port = ($_SERVER['SERVER_PORT'] == '80') ? '': ':'.$_SERVER['SERVER_PORT'];
        $path = '/';
        if(isset($_SERVER['PHP_SELF']))
        {
            $sv = trim($_SERVER['PHP_SELF'],'/');
            $uris = explode('/',$sv);
            foreach($uris as $uri)
            {
                if(empty($uri)){ continue; }
                if($uri === 'index.php'){ break; }
                $path .= $uri.'/';
            }
        }
        $scheme = (isset($_SERVER['REQUEST_SCHEME'])) ? $_SERVER['REQUEST_SCHEME'] : 'http';
        return $scheme.'://'.$_SERVER['SERVER_NAME'].$port.$path.$slug;
    }

    /**
     * @return string
     */
    public function IPAddress()
    {
        return $_SERVER['REMOTE_ADDR'];
    }

    /**
     * @return bool
     */
    public function IsAjaxRequest()
    {
        $headers = getallheaders();
        if($headers) return (strtolower($headers['X-Requested-With']) === 'xmlhttprequest');
        return false;
    }
}