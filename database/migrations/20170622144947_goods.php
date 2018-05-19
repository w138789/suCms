<?php

use think\migration\Migrator;
use think\migration\db\Column;

class Goods extends Migrator
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
        $this->table('goods', ['id' => 'goods_id', 'engine' => 'MyISAM', 'comment' => '商品表'])
            ->addColumn('goods_name', 'string', ['limit' => 60])
            ->addColumn('category_id', 'integer', ['limit' => 60, 'default' => 0])
            ->addColumn('brand_id', 'integer', ['limit' => 10, 'default' => 0])
            ->addColumn('goods_sn', 'string', ['limit' => 60, 'null' => true])
            ->addColumn('goods_number', 'integer', ['limit' => 10, 'default' => 0])
            ->addColumn('market_price', 'decimal', ['precision' => 10, 'scale' => 2, 'default' => '0.00'])
            ->addColumn('shop_price', 'decimal', ['precision' => 10, 'scale' => 2, 'default' => '0.00'])
            ->addColumn('keywords', 'string', ['limit' => 255, 'null' => true])
            ->addColumn('goods_desc', 'text', ['null' => true])
            ->addColumn('goods_thumb', 'string', ['limit' => 120, 'null' => true])
            ->addColumn('images', 'text', ['null' => true])
            ->addColumn('sort', 'integer', ['default' => 0, 'null' => true])
            ->addColumn('status', 'integer', ['limit' => 1, 'default' => 0, 'comment'=>'1 上架, 0 下架'])
            ->create();

    }
}
