<?php

use yii\db\Migration;

/**
 * Создание таблицы  `Картинки`.
 */
class m240312_185032_create_images_table extends Migration
{
    /**
     * Наименование таблицы, которая создается
     */
    const TABLE_NAME = 'images';

    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB COMMENT "Картинки"';
        }
        $this->createTable(self::TABLE_NAME, [
            'id' => $this->primaryKey(),
            'filename' => $this->string(100)->comment('Название'),
            'img_url' => $this->string()->comment('Путь до изображения'),
            'by_default' => $this->boolean()->comment('Картинка по умолчанию'),
            'id_objects' => $this->integer()->comment('id objects'),
            'thumb' => $this->string()->comment('Миниатюра изображения'),
            'created_at' => $this->integer()->comment('Дата создания'),

        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable(self::TABLE_NAME);
    }
}
