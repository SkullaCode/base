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
        return $_SERVER['HTTP_USER_AGENT'];
    }

    /**
     * @return string
     */
    public function HostName()
    {
        return $_SERVER['HTTP_HOST'];
    }

    /**
     * @return string
     */
    public function BaseURL()
    {
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
        return $scheme.'://'.$_SERVER['SERVER_NAME'].$port.$path;
    }

    /**
     * @return string
     */
    public function IPAddress()
    {
        return $_SERVER['REMOTE_ADDR'];
    }
}