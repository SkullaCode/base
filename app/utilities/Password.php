<?php


namespace App\Utility;


use App\Interfaces\IPassword;

class Password implements IPassword
{

    /**
     * @param string $password
     * @return string
     */
    public function Hash($password)
    {
        return password_hash($password,PASSWORD_DEFAULT);
    }

    /**
     * @param string $password
     * @param string $hash
     * @return boolean
     */
    public function Verify($password, $hash)
    {
        return password_verify($password,$hash);
    }
}