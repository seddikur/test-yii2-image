<?php

namespace app\models;

use app\helpers\ImageHelper;
use backend\modules\catalog\models\image\ItemImage;
use Symfony\Component\Filesystem\Filesystem;
use Yii;
use yii\helpers\VarDumper;

/**
 * This is the model class for table "images".
 *
 * @property int $id
 * @property string $filename Название
 * @property string $img_url Путь до изображения
 * @property int|null $is_cover Является ли это изображение текущим для вывода
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
    public static $uploadBasePath = '@webroot/uploads/';

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
            [['filename', 'img_url', 'created_at'], 'required'],
            [['created_at'], 'integer'],
            [['is_cover'], 'boolean'],
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
    public function upload()
    {
        $dirPath = self::$uploadBasePath . $this->id;
        $location = Yii::getAlias($dirPath);
        // Директория для загрузки изображения
        (new Filesystem)->mkdir($location, 0755);

        if(!$this->img_url) {
            $localPath = $location . DIRECTORY_SEPARATOR . $this->filename;
//            VarDumper::dump($localPath); die();
            $this->uploadedFile->saveAs($localPath);
        } else {
            $parsed = parse_url($this->img_url);
            // Для доменов на русском
//            $parsed['host'] = idn_to_ascii($parsed['host']);
//            $this->img_url = $this->unparse($parsed);

            $img = $this->convertImageToBase64($this->img_url);
            $img = str_replace('data:image/png;base64,', '', $img);
            $img = str_replace(' ', '+', $img);
            $data = base64_decode($img);
            $localPath = $location . DIRECTORY_SEPARATOR . $this->filename;
            file_put_contents($localPath, $data);
        }
        // Генерируем миниатюру
        $thumbPath = Yii::getAlias(self::$uploadBasePath . $this->id . '/thumbs');
        ImageHelper::thumbnail($localPath, 100, 100, $thumbPath);

        // Генерируем превью
        $fs = new Filesystem();
        if($fs->exists($localPath)) {
            list($width, $height) = getimagesize($localPath);
            (new ImageHelper())->generateImg($width, $height, 200, $localPath, Yii::getAlias(self::$uploadBasePath . $this->category_id . '/thumbs/XS'), $fs);
            (new ImageHelper())->generateImg($width, $height, 300, $localPath, Yii::getAlias(self::$uploadBasePath . $this->category_id . '/thumbs/SM'), $fs);
            (new ImageHelper())->generateImg($width, $height, 400, $localPath, Yii::getAlias(self::$uploadBasePath . $this->category_id . '/thumbs/MD'), $fs);
            (new ImageHelper())->generateImg($width, $height, 500, $localPath, Yii::getAlias(self::$uploadBasePath . $this->category_id . '/thumbs/LG'), $fs);
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
        $curlError = curl_error($curl_handle);
        curl_close($curl_handle);
        if ($img) {
            $imageData = base64_encode($img);
            return "data:image/png;base64,{$imageData}";
        }

        Yii::error("Error whiling download catalog item image - " . $curlError);
        return null;
    }

    /**
     * Ссылка на изображение
     * @return string
     */
    public function getImagePath()
    {
        return ($this->useFileImageWithWatermark() && is_file(\Yii::getAlias($this->getDirPath()) . $this->filename_watermark))
            ? '/uploads/catalog/' . $this->item_id . '/' . $this->filename_watermark
            : '/uploads/catalog/' . $this->item_id . '/' . $this->filename;
    }

    /**
     * Ссылка на миниатюру
     * @return string
     */
    public function getThumb()
    {
        return ($this->useFileImageWithWatermark() && is_file(\Yii::getAlias($this->getDirPath()) . 'thumbs/' . $this->filename_watermark))
            ? '/uploads/catalog/' . $this->item_id . '/thumbs/' . $this->filename_watermark
            : '/uploads/catalog/' . $this->item_id . '/thumbs/' . $this->filename;
    }

    /**
     * Путь до директории, в которой находится изображение
     * @return string
     */
    public function getDirPath()
    {
        return self::$uploadBasePath . $this->item_id . DIRECTORY_SEPARATOR;
    }

    /**
     * Полный путь до изображения
     * @return string
     */
    public function getFullPath()
    {
        return self::$uploadBasePath . $this->item_id . DIRECTORY_SEPARATOR . $this->filename;
    }
}
