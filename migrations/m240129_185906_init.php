<?php

use yii\db\Migration;

class m240129_185906_init extends Migration
{
    private const TABLE_BOXES = 'boxes';
    private const TABLE_PRODUCTS = 'products';

    public function safeUp()
    {
        $this->createTable(self::TABLE_BOXES, [
            'id' => $this->primaryKey(),
            'reference' => $this->string()->notNull(),
            'weight' => $this->decimal(10, 2),
            'length' => $this->decimal(10, 2),
            'height' => $this->decimal(10, 2),
            'width' => $this->decimal(10, 2),
            'status' => $this->string(12)->notNull(),
            'total_price' => $this->decimal(20, 2),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);

        $this->createTable(self::TABLE_PRODUCTS,[
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'SKU' => $this->string(50)->notNull()->unique(),
            'shipped_qty' => $this->integer(),
            'received_qty' => $this->integer(),
            'price' => $this->decimal(10,2),
            'box_id' => $this->integer(),
        ]);

        $this->addForeignKey(
            self::TABLE_PRODUCTS . '-fk-box_id',
            self::TABLE_PRODUCTS,
            'box_id',
            self::TABLE_BOXES,
            'id',
            'cascade',
            'cascade'

        );
    }

    public function safeDown()
    {
        $this->dropTable(self::TABLE_PRODUCTS);
        $this->dropTable(self::TABLE_BOXES);
    }
}
