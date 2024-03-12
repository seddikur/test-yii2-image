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
            'filename' => $this->string(100)->notNull()->comment('Название'),
            'img_url' => $this->string()->notNull()->comment('Путь до изображения'),
            'is_cover' => $this->integer()->defaultValue(1)->comment('Является ли это изображение текущим для вывода'),
            'created_at' => $this->integer()->notNull()->comment('Дата создания'),
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
