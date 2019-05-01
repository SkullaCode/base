<?php


namespace App\DBContext;


use App\Model\StateCM;
use Psr\Container\ContainerInterface;

class State extends BaseDbContext
{
    private $JamaicaState;

    public function __construct(ContainerInterface $c)
    {
        parent::__construct(new StateCM(), $c);
        $this->Table = "states";
        $this->TypeMapper = [
            'CountryID'     =>  'int'
        ];
        $this->JamaicaState = (int)$c->get('settings')['state_id'];
    }

    public function FindAllStatesByCountry($country)
    {
        return $this->GetAllWithQuery(
            ['id'=>$country],
            function(BaseDbContext $c,$p){
                return [
                    $c->Field('CountryID')      =>  $p['id'],
                    "ORDER"                     =>  [
                        $c->Field("Name")   =>  "ASC"
                    ]
                ];
            }
        );
    }

    public function FindAllStatesForJamaica()
    {
        return $this->FindAllStatesByCountry($this->JamaicaState);
    }
}