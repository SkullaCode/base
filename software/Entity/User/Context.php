<?php


namespace Software\Entity\User;


use App\DBContext\BaseDbContext;
use App\Utility\DateTime;
use Psr\Container\ContainerInterface;
use Software\Entity\User\Constants\Status;

class Context extends BaseDbContext
{
    public function __construct(ContainerInterface $c)
    {
        /**
         * @var DateTime $date
         */
        $date = $c->get('DateTimeUtility');
        parent::__construct(new Model(),'users',$c);
        $this->ID = "id";
        $this->Mapper = [
            'ID'                =>  'id',
            'UserName'          =>  'username',
            'Password'          =>  'password',
            'EmailAddress'      =>  'email',
            'Verified'          =>  ['verified','bool'],
            'Resettable'        =>  ['resettable','bool'],
            'RolesMask'         =>  ['roles_mask','integer'],
            'ForceLogout'       =>  ['force_logout','bool']
        ];
        $this->Transformer = [
            'Status'        =>  function($result){
                return Status::ToString($result['status']);
            },
            'Registered'    =>  function($result) use($date){
                return $date->FormatTimeStampToDate($result['registered']);
            },
            'LastLogin'     =>  function($result) use($date){
                if(is_null($result['last_login'])) return null;
                return $date->FormatTimeStampToDate($result['last_login']);
            }
        ];
    }
}