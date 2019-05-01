<?php


use Phinx\Migration\AbstractMigration;

class Session extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    addCustomColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Any other destructive changes will result in an error when trying to
     * rollback the migration.
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {
        $db = $this->table('sessions',['id' => 'ID']);
        $db
            ->addColumn('Token','string',['limit' => 150])
            ->addColumn('UID','string',['limit' => 150])
            ->addColumn('EmailAddress','string',['limit' => 150])
            ->addColumn('FirstName','string',['limit' => 150])
            ->addColumn('LastName','string',['limit' => 150])
            ->addColumn('IPAddress','string',['limit' => 150])
            ->addColumn('UserAgent','string',['limit' => 150])
            ->addColumn('LoggedInAt','datetime')
            ->addColumn('Expires','datetime')
            ->addColumn('cts','datetime')
            ->addColumn('mts','datetime')
            ->addColumn('cby','string',['limit' => 150])
            ->addColumn('mby','string',['limit' => 150])
            ->addColumn('Status','integer')
            ->create();
    }
}
