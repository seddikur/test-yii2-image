<?php

namespace app\models;

use app\helpers\ImageHelper;
use app\helpers\HelperName;
use Symfony\Component\Filesystem\Filesystem;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\helpers\VarDumper;
use yii\web\UploadedFile;

/**
 * Модель хранит данные о картинках.
 *
 * @property int $id
 * @property string $filename Название
 * @property string $img_url Путь до изображения
 * @property int|null $by_default Картинка по умолчанию
 * @property string $thumb Миниатюра изображения
 * @property int|null $id_objects id objects
 * @property int|null $created_at Дата создания
 */
class Images extends \yii\db\ActiveRecord
{
    /**
     * Массив с изображениями
     * @var \yii\web\UploadedFile[]
     */
    public $imageFile;

    /** @var \yii\web\UploadedFile $uploadedFile */
    public $uploadedFile;

    /**
     * Путь для загрузки изображения
     * @var string
     */
    public static $uploadBasePath = '@webroot/uploads';

    /** @var string $urlPrefix Префикс URL для изображений  */
    public static $urlPrefix = '/uploads';

    /**
     * Наименование таблицы
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'images';
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

    /**
     * Правила валидации полей
     * @return array
     */
    public function rules()
    {
        return [
//            [['imageFile'], 'required', 'message' => 'Необходимо выбрать «Картинку»'],
            [['filename', 'img_url'], 'string', 'max' => 100],
//            ['filename', 'unique'], //проверка на уникальность

            [['by_default', 'id_objects', 'created_at'], 'integer'],

            [['img_url'], 'string', 'max' => 255],
            [['imageFile'], 'safe'],
            [['imageFile'], 'file',
                'checkExtensionByMimeType' => false,
//                'extensions' => 'png, jpg, jpeg',
                'extensions' => ['jpg', 'jpeg', 'png', 'gif'],
                'skipOnEmpty' => true,
                'maxFiles' => 5
            ],
        ];
    }

    /**
     * Наименование аттрибутов
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'filename' => 'Название',
            'img_url' => 'Путь до изображения',
            'by_default' => 'Картинка по умолчанию',
            'id_objects' => 'id objects',
            'imageFile' => 'Файл',
            'created_at' => 'Дата создания',
        ];
    }

    /**
     * Загрузка изображений и сохранение в базу данных.
     * @param UploadedFile[] $imageFile
     */
    public function loadImages($imageFile): void
    {
        $imagesModels = [];
        $transaction = Yii::$app->db->beginTransaction();
        foreach ($imageFile as $file) {
            $modelImage = new Images;
            //название файла
            $file_name =HelperName::transliteration($file->baseName);
            //\md5($file->baseName) . mt_rand(0, 3) .'.' . $file->extension;

//            echo $file_name;
//            VarDumper::dump(self::findOne(['filename'=>$file_name])); die();
            //проверяем по базе на уникальность
           if (self::findOne(['filename'=>$file_name.'.' . $file->extension]))
            {
                $modelImage->filename = $file_name . mt_rand(0, 3) .'.' . $file->extension;

            }else{
               $modelImage->filename = $file_name.'.' . $file->extension;

           }

            $modelImage->img_url = self::$urlPrefix;
            $modelImage->created_at = time();
            $modelImage->uploadedFile = $file;
            $imagesModels[] = $modelImage;
            $modelImage->saveFiles();
            $modelImage->save();
        }
        $transaction->commit();
        $this->populateRelation('images', $imagesModels);
    }

    /**
     * Сохранить загруженное изображение в директорию
     */
    public function saveFiles()
    {
        $dirPath = self::$uploadBasePath . $this->id;
        $location = Yii::getAlias($dirPath);
        // Директория для загрузки изображения
        (new Filesystem)->mkdir($location, 0755);


            $localPath = $location . DIRECTORY_SEPARATOR . $this->filename;
            $this->uploadedFile->saveAs($localPath);

        // Генерируем миниатюру
        $thumbPath = Yii::getAlias(self::$uploadBasePath . $this->id . '/thumbs');
        ImageHelper::thumbnail($localPath, 100, 100, $thumbPath);

      //   Генерируем превью
        $fs = new Filesystem();
        if($fs->exists($localPath)) {
//            list($width, $height) = getimagesize($localPath);
//            (new ImageHelper())->generateImg($width, $height, 200, $localPath, Yii::getAlias(self::$uploadBasePath . $this->category_id . '/thumbs/XS'), $fs);
//            (new ImageHelper())->generateImg($width, $height, 300, $localPath, Yii::getAlias(self::$uploadBasePath . $this->category_id . '/thumbs/SM'), $fs);
//            (new ImageHelper())->generateImg($width, $height, 400, $localPath, Yii::getAlias(self::$uploadBasePath . $this->category_id . '/thumbs/MD'), $fs);
//            (new ImageHelper())->generateImg($width, $height, 500, $localPath, Yii::getAlias(self::$uploadBasePath . $this->category_id . '/thumbs/LG'), $fs);
        }
    }


    /**
     * Ссылка на изображение
     * @return string
     */
    public function getImagePath()
    {
        return (is_file(\Yii::getAlias($this->getDirPath()) . $this->filename))
            ? '/uploads/' . $this->filename
            : '';
    }

    /**
     * Ссылка на миниатюру
     * @return string
     */
    public function getThumb()
    {
        return (is_file(\Yii::getAlias($this->getDirPath()) . 'thumbs/' . $this->filename))
            ? '/uploads/' . '/thumbs/' . $this->filename
            : '';
    }

    /**
     * Путь до директории, в которой находится изображение
     * @return string
     */
    public function getDirPath()
    {
        return self::$uploadBasePath . DIRECTORY_SEPARATOR;
    }

    /**
     * Полный путь до изображения
     * @return string
     */
    public function getFullPath()
    {
        return self::$uploadBasePath . DIRECTORY_SEPARATOR . $this->filename;
    }

    /**
     * Полный путь до миниатюры
     * @return string
     */
    public function getFullPathThumb()
    {
        return self::$uploadBasePath .  '/thumbs/' . $this->filename;
    }

    /**
     * Загрузка изображений через url.
     * @param array $imageUrl
     */
    public function loadImagesByUrl($imageUrl) : void
    {

        $imagesModels = [];
        $transaction = Yii::$app->db->beginTransaction();
        foreach ($imageUrl as $url) {
            if (mb_strtolower(substr($url, 0, 1)) == ',') {
                $url = substr($url, 1);
            }
            $url = trim($url);

            $filename = md5(uniqid('', true));
            $modelImage = new Images();
            $modelImage->filename = $filename . '.png';
            $modelImage->img_url = $url;
            $imagesModels[] = $modelImage;
        }
        $transaction->commit();
        $this->populateRelation('images', $imagesModels);
    }

    /*****************************************************************************************************************
     *                                                                                                  STATIC METHODS
     *****************************************************************************************************************/

    /**
     * Абсолютный путь до изображения.
     * @param string $file
     * @return string
     */
    public static function makePath( string $file) : string
    {
        return self::$urlPrefix  . DIRECTORY_SEPARATOR . $file;
    }

    /**
     * @param $file
     * @return string
     */
    public static function makeThumbUrl($file)
    {
        return self::$urlPrefix .  DIRECTORY_SEPARATOR . 'thumbs/' . $file;
    }

    /**
     * @param $file
     * @return string
     */
    public static function makeUrl($file)
    {
        return self::$urlPrefix .  DIRECTORY_SEPARATOR . $file;
    }

    /**
     * Конвертирует изображение в base64.
     * @param $path - путь к изображению
     * @return string|null изображения
     */
    public static function convertImageToBase64($path) :? string
    {
        $url = $path;
        $curl_handle=curl_init();
        curl_setopt($curl_handle, CURLOPT_URL,$url);
        curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2);
        curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl_handle, CURLOPT_USERAGENT, 'Get img');
        $img = curl_exec($curl_handle);
        curl_close($curl_handle);
        if($img) {
            $imageData = base64_encode($img);
            return "data:image/png;base64,{$imageData}";
        }
        return null;
    }

    /*****************************************************************************************************************
     *                                                                                                       RELATIONS
     *****************************************************************************************************************/
}
