<?php

use think\migration\Migrator;
use think\migration\db\Column;

class GoodsAttr extends Migrator
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
        $this->table('goods_attr', ['id' => 'attr_id', 'engine' => 'MyISAM', 'comment' => '商品规格表'])
            ->addColumn('title', 'string', ['limit' => 60])
            ->addColumn('value', 'string', ['limit' => 255])
            ->addColumn('pid', 'integer', ['limit' => 10,'default'=>0])
            ->create();
    }
}
