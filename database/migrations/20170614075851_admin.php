<?php

use think\migration\Migrator;
use think\migration\db\Column;

class Admin extends Migrator
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
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {
        $this->table('admin', ['id' => 'admin_id', 'engine' => 'MyISAM', 'comment' => '管理员账号表'])
            ->addColumn('username', 'string', ['limit' => 60])
            ->addColumn('password', 'string', ['limit' => 60])
            ->addColumn('salt', 'string', ['limit' => 10])
            ->addColumn('email', 'string', ['limit' => 60])
            ->addColumn('last_login_ip', 'string', ['limit' => 15])
            ->addColumn('create_time', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('update_time', 'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'update' => 'CURRENT_TIMESTAMP'])
            ->create();
        $data = [
            'username'      => 'admin',
            'password'      => '70613a12a2c4b98d196a34b1466a9c6a',
            'salt'          => 'S7E3sd',
            'email'         => '294496623@qq.com',
            'last_login_ip' => '192.168.3.201',
        ];
        $this->table('admin')->insert($data)->saveData();
    }
}
