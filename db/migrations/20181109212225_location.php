<?php


use Phinx\Migration\AbstractMigration;

class Location extends AbstractMigration
{
    public function change()
    {
        if(!$this->hasTable('countries'))
        {
            $country = $this->table('countries',['id' => 'ID']);
            $country
                ->addColumn('Name','string',['limit' => 50])
                ->addColumn('cts','datetime')
                ->addColumn('mts','datetime')
                ->addColumn('cby','string',['limit' => 150])
                ->addColumn('mby','string',['limit' => 150])
                ->addColumn('Status','integer')
                ->create();

            $state = $this->table('states',['id' => 'ID']);
            $state
                ->addColumn('Name','string',['limit' => 50])
                ->addColumn('CountryID','integer')
                ->addColumn('cts','datetime')
                ->addColumn('mts','datetime')
                ->addColumn('cby','string',['limit' => 150])
                ->addColumn('mby','string',['limit' => 150])
                ->addColumn('Status','integer')
                ->create();
        }

        $db = $this->table('locations');
        $db
            ->addColumn('AddressID','string',['limit' => 150])
            ->addColumn('PrimaryContactNumber','string',['limit' => 150])
            ->addColumn('SecondaryContactNumber','string',['limit' => 150])
            ->addColumn('Latitude','string',['limit' => 150])
            ->addColumn('Longitude','integer')
            ->addColumn('DeliveryDate','datetime')
            ->addColumn('DeliveryWindow','string',['limit' => 150])
            ->addColumn('ContactName','string',['limit' => 150])
            ->addColumn('Directions','string',['limit' => 250])
            ->addColumn('cts','datetime')
            ->addColumn('mts','datetime')
            ->addColumn('cby','string',['limit' => 150])
            ->addColumn('mby','string',['limit' => 150])
            ->addColumn('Status','integer')
            ->create();
    }
}
