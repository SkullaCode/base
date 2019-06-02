<?php


namespace App\Utility;


use App\Interfaces\ICodeGenerator;

class CodeGenerator implements ICodeGenerator
{

    /**
     * @param int $length the length of the resulting string
     * @param string $type the type of string that should be generated
     * @return string
     */
    private function code($length, $type = 'alpha-num')
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        switch($type)
        {
            case 'alpha-num'	:{break;}
            case 'alpha-upper' 	:{$characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'; break;}
            case 'alpha-lower' 	:{$characters = 'abcdefghijklmnopqrstuvwxyz'; break;}
            case 'alpha-mixed' 	:{$characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'; break;}
            case 'num' 			:{$characters = '0123456789'; break;}
        }
        $charactersLength = strlen($characters);
        $randomString = '';
        $buffer = '';
        for ($i = 0; $i < $length; $i++)
        {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
            $buffer .= $characters[rand(0, $charactersLength - 1)];
        }
        $randomString = str_ireplace(array('css','js'),'',$randomString);
        $buffer = str_ireplace(array('css','js'),'',$buffer);
        if(strlen($randomString) < $length)
        {
            $randomString .= $buffer;
            return substr($randomString,0,$length);
        }
        else
        {
            return $randomString;
        }
    }

    /**
     * @return string
     */
    public function ResetAccount()
    {
        return self::code(64,'alpha-num');
    }

    /**
     * @return string
     */
    public function Cookie()
    {
        return self::code(32,'alpha-mixed');
    }


    /**
     * @param int $length length of string returned
     * @return string
     */
    public function AlphaString($length)
    {
        return self::code($length,'alpha-mixed');
    }

    /**
     * @param int $length length of string returned
     * @return string
     */
    public function AlphaNumericString($length)
    {
        return self::code($length);
    }

    /**
     * @return string
     */
    public function SessionToken()
    {
        return self::code(128,'alpha-mixed');
    }

    /**
     * @return string
     */
    public function ResourceHash()
    {
        return self::code(16,'alpha');
    }
}