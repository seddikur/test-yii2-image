<?php

namespace app\models;

use app\helpers\ImageHelper;
use Symfony\Component\Filesystem\Filesystem;
use Yii;
use yii\helpers\VarDumper;
use yii\web\UploadedFile;

/**
 * This is the model class for table "images".
 *
 * @property int $id
 * @property string $filename Название
 * @property string $img_url Путь до изображения
 * @property int $created_at Дата создания
 */
class Images extends \yii\db\ActiveRecord
{
    /**
     * Массив с изображениями
     * @var \yii\web\UploadedFile[]
     */
    public $images;

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
     * Правила валидации полей
     * @return array
     */
    public function rules()
    {
        return [
            [['filename', 'img_url'], 'required'],
            [['created_at'], 'integer'],
            [['filename', 'img_url'], 'string', 'max' => 100],
            [['images'], 'safe'],
            [['images'], 'file',
                'checkExtensionByMimeType' => false,
                'extensions' => 'png, jpg, jpeg',
                'skipOnEmpty' => true,
                'maxFiles' => 10
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
            'created_at' => 'Дата создания',
        ];
    }

    /**
     * Сохранить загруженное изображение
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

    /**
     * Ссылка на изображение
     * @return string
     */
    public function getImagePath()
    {
        return (is_file(\Yii::getAlias($this->getDirPath()) . $this->filename))
            ? '/uploads/' . $this->id . '/' . $this->filename
            : '';
    }

    /**
     * Ссылка на миниатюру
     * @return string
     */
    public function getThumb()
    {
        return (is_file(\Yii::getAlias($this->getDirPath()) . 'thumbs/' . $this->filename))
            ? '/uploads/' . $this->id . '/thumbs/' . $this->filename
            : '';
    }

    /**
     * Путь до директории, в которой находится изображение
     * @return string
     */
    public function getDirPath()
    {
        return self::$uploadBasePath . $this->id . DIRECTORY_SEPARATOR;
    }

    /**
     * Полный путь до изображения
     * @return string
     */
    public function getFullPath()
    {
        return self::$uploadBasePath . $this->id . DIRECTORY_SEPARATOR . $this->filename;
    }

    /**
     * Загрузка изображений.
     * @param UploadedFile[] $images
     */
    public function loadImages($images): void
    {
        $imagesModels = [];
        $transaction = Yii::$app->db->beginTransaction();
        foreach ($images as $file) {
            $modelImage = new Images;
            $modelImage->filename = \md5($file->baseName) . '.' . $file->extension;
            $modelImage->img_url = self::$urlPrefix;
            $modelImage->created_at = time();
            $modelImage->uploadedFile = $file;
            $imagesModels[] = $modelImage;
            $modelImage->saveFiles($images);
            $modelImage->save();
        }
        $transaction->commit();
        $this->populateRelation('images', $imagesModels);
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
}
