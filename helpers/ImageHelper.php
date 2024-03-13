<?php

namespace app\helpers;

use app\models\Images;
use Exception;
use Symfony\Component\Filesystem\Filesystem;
use Yii;
use Imagine\Image\Box;
use Imagine\Image\ImageInterface;
use yii\imagine\Image;

/**
 *
 * Class ImageHelper
 */
final class ImageHelper
{
    /**
     * @var int Качество изображения
     */
    public const IMAGE_QUALITY = 90;


    /**
     * Загрузка изображения по указанному URL.
     *
     * @param string $location URL изображения для закачки
     * @param string $path Путь для сохранения
     *
     * @return mixed
     * @throws Exception
     */
    public static function download($location, $path) : string
    {
        // Разбиваем URL, формируем массив
        $handleImg = \explode('/', $location);

        if (\is_array($handleImg) && !empty($handleImg)) {
            /** @var string $name Имя оригинального файла */
            $name = \end($handleImg);
            /** @var string $extension Расширение файла */
            $extension = self::extension($name);
            /** @var string $fileName Новое имя файла - генерируем уникальное */
            $fileName = \uniqid() . $extension;
            /** @var string $newUrl Путь для закачки изображения */
            $newUrl = $path . $fileName;

            if ($newUrl) {
                $fp = \fopen($newUrl, 'w+');
                $ch = \curl_init($location);

                // Настройка CURL
                \curl_setopt_array($ch, [
                    CURLOPT_USERAGENT => 'Mozilla/5.0', // Указываем User-агент
                    CURLOPT_FILE => $fp,
                    CURLOPT_RETURNTRANSFER => true,
                ]);

                // Начинаем сессию curl
                $dataImage = \curl_exec($ch);
                if ($dataImage === false) {
                    echo 'Не удалось загрузить изображение';
                    if ($curlErrno = \curl_errno($ch)) {
                        $errorMessage = \curl_strerror($curlErrno);
                        $errorMessage = "cURL error ({$curlErrno}):\n {$errorMessage}" . PHP_EOL;
                        throw new Exception($errorMessage);
                    }
                }

                // Завершаем сессию curl
                \curl_close($ch);
                if ($dataImage) {
                    \fwrite($fp, $dataImage);
                    \fclose($fp);
                }

                return $fileName;
            }
        }
    }

    /**
     * Получить расширение файла.
     *
     * @param string $path Путь до файла
     *
     * @return string
     */
    public static function extension(string $path) : string
    {
        return \strrchr($path, '.');
    }

    /**
     * Создать миниатюру изображения.
     *
     * @param $localPath
     * @param $width
     * @param $height
     * @param $thumbPath
     *
     * @return ImageInterface
     */
    public static function thumbnail($localPath, $width, $height, $thumbPath) : ImageInterface
    {
        return Image::getImagine()->open(self::makeThumbnail($localPath, $width, $height, $thumbPath));
    }

    /**
     * Создание миниатюры
     *
     * @param $localPath
     * @param $width
     * @param $height
     * @param $thumbPath
     *
     * @return bool|string
     * @throws Exception
     */
    private static function makeThumbnail($localPath, $width, $height, $thumbPath)
    {
        $fs = new Filesystem();
        if (!$fs->exists($localPath)) {
            throw new Exception("изображение {$localPath} не найдено!");
        }

        //$ext = self::extension($localPath);

        $handle = \explode('/', $localPath);
        /** @var string $name Имя оригинального файла */
        $name = \end($handle);

        $thumbName = $name;
        $thumbPathNew = $thumbPath;

        $thumbnailFile = Yii::getAlias($thumbPathNew . DIRECTORY_SEPARATOR . $thumbName);

        // Создаем папку для миниатюры
        if (!\is_dir($thumbPathNew)) {
            $fs->mkdir($thumbPathNew, 0755);
        }

        $options = [
            'quality' => self::IMAGE_QUALITY
        ];

        /** @var Box $box */
        $box = new Box($width, $height);

        /** @var ImageInterface $image */
        $image = Image::getImagine()->open($localPath);
        $image = $image->thumbnail($box);
        $image->save($thumbnailFile, $options);

        return $thumbnailFile;
    }



    /**
     * Генерация изображений разного размера
     *
     * @param int $width ширина оригинальной картинки
     * @param int $height высота оригинальной картинки
     * @param int $size
     * @param string $fullPath
     * @param string $thumbPath
     * @param object $fileSystem
     * @param string $localPathWatermark
     */
    public function generateImg(int $width, int $height, int $size, string $fullPath, string $thumbPath, object $fileSystem, string $localPathWatermark = null) {
//        if(!$fileSystem->exists($thumbPath)) {
        if($width >= $height) {
            $width = $size;
            $height = $height * $size / $width;
        } else {
            $height = $size;
            $width = $width * $size / $height;
        }
        ImageHelper::thumbnail($fullPath, $width, $height, $thumbPath);
        if ($localPathWatermark) {
            ImageHelper::thumbnail($localPathWatermark, $width, $height, $thumbPath);
        }
//        }
    }





}
