<?php
namespace app\components;

use app\helpers\ImageHelper;
use Symfony\Component\Filesystem\Filesystem;
use Yii;
use yii\db\ActiveRecord;
use yii\imagine\Image;
use yii\web\UploadedFile;
use Exception;

/**
 * @property ActiveRecord $model
 *
 * Class Imgload
 * У передаваемого объекта предполагается поле в БД `avatar`, где храним только название изображения
 */

class Imgload
{

    /**
     * Массив с изображениями
     * @var \yii\web\UploadedFile[]
     */
    public $array_images;

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
     * Константа картинки, которая передается в форму
     */
    public $imageFile;

    /**
     * Константа поля в таблице, где храниться название картинки
     */
    public $imageAtribut;

    /**
     * Компонент, в котором храниться данные модели к которой прикреплен
     */
    public $owner;

    /**
     * Обновление картинки
     * @var $currentImage string Старое изображение
     * $image UploadedFile::getInstance($model, 'imageFile')
     * @throws Exception
     */
    public function LoadImages(UploadedFile $image, $currentImage = null) : void
    {
        //валидация с помощью валидатора модели
        $this->owner->validate();

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $this->saveImage();
            $transaction->commit();
        } catch (Exception $e) {
            $transaction->rollBack();
            throw $e;
        }

        $this->notify();

    }

    /**
     * Сохранение картинки
     * @return string
     */
    public function saveImage(): string
    {

//        Генрация название файла
        $filename = $this->generateFilename();

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

        return $filename;
    }

    /**
     * Генрация название файла
     * strtolower - Преобразовывает строку в нижний регистр
     * file_exists - Проверяет существование указанного файла или каталога
     * @return string
     */
    public function generateFileName(): string
    {
        do {
//            \md5($file->baseName) . '.' . $file->extension;
            $name = substr(md5(microtime() . rand(0, 1000)), 0, 20);
            $file = strtolower($name . '.' . $this->imageFile->extension);
        } while (file_exists($file));

        return $file;
    }



    /**
     * Уведомление о созданной .
     */
    private function notify() : void
    {
        Yii::$app->noty->success("{$this->typeText} {$this->catalogItem->title} успешно создан!");
    }


}