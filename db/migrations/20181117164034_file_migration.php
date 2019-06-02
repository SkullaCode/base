<?php


use Phinx\Migration\AbstractMigration;

class FileMigration extends AbstractMigration
{
    public function change()
    {
        $file = $this->table('files',['id' => false, 'primary_key' => 'ID']);
        $file
            ->addColumn('ID','string',['limit' => 64])
            ->addColumn('Name','string',['limit' => 150])
            ->addColumn('Extension','string',['limit' => 10])
            ->addColumn('MIME','string',['limit' => 50])
            ->addColumn('Content','text')
            ->addColumn('Size','integer')
            ->addColumn('cts','datetime')
            ->addColumn('mts','datetime')
            ->addColumn('cby','string',['limit' => 150])
            ->addColumn('mby','string',['limit' => 150])
            ->addColumn('Status','integer')
        ->create();
    }
}
