<?php


use Phinx\Seed\AbstractSeed;

class CountrySeeder extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     */
    public function run()
    {
        $res = $this->query("SELECT COUNT(id) AS count FROM countries");
        $count = $res->fetch();
        if((int)$count['count'] <= 0)
        {
            $location =(
                __DIR__ .
                DIRECTORY_SEPARATOR.
                '..'.
                DIRECTORY_SEPARATOR.
                '..'.
                DIRECTORY_SEPARATOR.
                'country_list.json'
            );
            if(file_exists($location))
            {
                $file = file_get_contents($location);
                if($file !== false)
                {
                    $countries = $this->table('countries');
                    $states = $this->table('states');
                    $file = (array)json_decode($file);
                    foreach($file as $rec => $item)
                    {
                        $countries->insert([
                            'Name'          => $rec,
                            'Status'        =>  \App\Constant\StatusCode::ACTIVE,
                            'cts'           =>  date('Y-m-d H:i:s'),
                            'mts'           =>  date('Y-m-d H:i:s')
                        ])->save();
                        $country_id = $this->getAdapter()->getConnection()->lastInsertId();
                        foreach($item as $val)
                        {
                            $states->insert([
                                'Name'          =>  $val,
                                'CountryID'     =>  $country_id,
                                'Status'        =>  \App\Constant\StatusCode::ACTIVE,
                                'cts'           =>  date('Y-m-d H:i:s'),
                                'mts'           =>  date('Y-m-d H:i:s')
                            ])->save();
                        }
                    }
                }
            }
        }
    }
}
