<?php


use Phinx\Migration\AbstractMigration;

class AuthenticationMigration extends AbstractMigration
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
        $table  = $this->table("users");
        $table
            ->addColumn('email','string',['limit' => 249, 'null' => false])
            ->addColumn('password','string',['limit' => 255, 'null' => false])
            ->addColumn('username','string',['limit' => 100])
            ->addColumn('status','integer',['null' => false,'default' => 0])
            ->addColumn('verified','integer',['null' => false, 'default' => 0])
            ->addColumn('resettable','integer',['null' => false, 'default' => 1])
            ->addColumn('roles_mask','integer',['null' => false, 'default' => 0])
            ->addColumn('registered','integer',['null' => false, 'default' => 0])
            ->addColumn('last_login','integer',['null' => true, 'default' => null])
            ->addColumn('force_logout','integer',['null' => false, 'default' => 0])
            ->addIndex(['email'],['unique' => true,'name' => 'idx_users_email'])
        ->create();

        $table = $this->table('users_confirmations');
        $table
            ->addColumn('user_id','integer',['null' => false])
            ->addColumn('email','string',['limit' => 249, 'null' => false])
            ->addColumn('selector','string',['limit' => 16])
            ->addColumn('token','string',['limit' => 255])
            ->addColumn('expires','integer',['null' => false])
            ->addIndex(['selector'],['unique' => true, 'name' => 'idx_users_confirmations_selector'])
            ->addIndex(['email','expires'])
            ->addIndex(['user_id'])
        ->create();

        $table = $this->table('users_remembered');
        $table
            ->addColumn('user','integer',['null' => false])
            ->addColumn('selector','string',['limit' => 20, 'null' => false])
            ->addColumn('token','string',['limit' => 255, 'null' => false])
            ->addColumn('expires','integer',['null' => false])
            ->addIndex(['selector'],['unique' => true, 'name' => 'idx_users_remembered_selector'])
            ->addIndex(['user'])
        ->create();

        $table = $this->table('users_resets');
        $table
            ->addColumn('user','integer',['null' => false])
            ->addColumn('selector','string',['limit' => 20, 'null' => false])
            ->addColumn('token','string',['limit' => 255, 'null' => false])
            ->addColumn('expires','integer',['null' => false])
            ->addIndex(['selector'],['unique' => true, 'name' => 'idx_users_resets_selector'])
            ->addIndex(['user','expires'])
        ->create();

        $table = $this->table('users_throttling',['id' => false, 'primary_key' => 'bucket']);
        $table
            ->addColumn('bucket','string',['limit' => 44, 'null' => false])
            ->addColumn('tokens','float',['null' => false])
            ->addColumn('replenished_at','integer',['null' => false])
            ->addColumn('expires_at','integer',['null' => false])
            ->addIndex(['expires_at'])
        ->create();
    }
}
