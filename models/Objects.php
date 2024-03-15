<?php

namespace app\models;

use Yii;
use app\components\Imgload;
use yii\behaviors\TimestampBehavior;

/**
 * Модель хранит данные по объекту.
 *
 * @property int $id
 * @property string $title Название
 * @property int|null $created_at Дата создания
 */
class Objects extends \yii\db\ActiveRecord
{

    public $imageFile;

    /**
     * Наименование таблицы
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'objects';
    }

    /**
     * Правила валидации полей
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['created_at'], 'integer'],
            [['title'], 'string', 'max' => 255],

            [['imageFile'], 'image',
                'extensions' => ['jpg', 'jpeg', 'png', 'gif'],
                'checkExtensionByMimeType' => true,
                'maxSize' => 1024000, // 500 килобайт = 500 * 1024 байта = 512 000 байт
                'tooBig' => 'Лимит 1МB'
            ],
        ];
    }

    /**
     * Наименование аттрибутов
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Название',
            'created_at' => 'Дата создания',
            'imageFile' => 'Rfhnb',
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'attributes' => [
                    self::EVENT_BEFORE_INSERT => ['created_at'],
                ],
                'preserveNonEmptyValues' => true,
            ],
        ];
    }
}
