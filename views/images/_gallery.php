<?php
use yii\helpers\Url;

use \app\models\Images;

use branchonline\lightbox\Lightbox;

/**
 * @var \app\models\Images $model
 */
\yii\helpers\VarDumper::dump($model->filename);
?>

<?php if (!empty($model->filename)) : ?>
    <?php foreach ($model->images as $img) : ?>
        <div class="thumbnail-sm pull-left <?= $img->is_cover ? "isCover" : ''?>" <?= $img->is_cover ? "style='border: 1px solid #00a65a; box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075), 0 0 8px rgba(102, 233, 124, 0.6);'" : ''?>>
            <?= Lightbox::widget([
                'files' => [
                    [
                        'thumb' => $img->thumb,
                        'original' => $img->imagePath,
                        'linkOptions' => [
                            'data-lightbox' => 'catalog-item-' . $img->item_id,
                        ],
                        'title' => $img->comment,
                    ],
                ]
            ]); ?>
            <?php if ($model->scenario != 'view') : ?>
                <a href="<?= Url::to(['/item/set-image-to-show', 'imageId' => $img->id]) ?>"
                   class="btn btn-xs btn-success catalog-item-set-img"
                   style="position:absolute; top:5px; left:5px; margin:0;"
                   data-toggle="tooltip"
                   title="Установить изображение">
                    <i class="fa fa-sm  fa-heart-o"></i>
                </a>
                <a href="<?= Url::to(['/item/remove-image', 'imageId' => $img->id]) ?>"
                   class="btn btn-xs btn-danger catalog-item-remove-img"
                   style="position:absolute; top:5px; right:5px; margin:0;"
                   data-toggle="tooltip"
                   title="Удалить изображение">
                    <i class="fa fa-sm fa-times"></i>
                </a>

            <?php endif ?>
        </div>
    <?php endforeach ?>

    <div class="clearfix"></div>


        <div class="row">
            <hr style="margin: 10px 0;"/>
        </div>
<?php else : ?>

        <div class="help-me">
            Изображений нет
        </div>

<?php endif ?>