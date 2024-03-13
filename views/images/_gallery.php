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
ffffffffffff
        <?= Lightbox::widget([
                'files' => [
                    [
                        'thumb' => $model->filename,
                        'original' => $model->filename,
                        'linkOptions' => [
                            'data-lightbox' => 'catalog-item-' . $model->id,
                        ],
                        'title' => $model->filename,
                    ],
                ]
            ]); ?>




            <?php if ($model->scenario != 'view') : ?>
                <a href="<?= Url::to(['/item/set-image-to-show', 'imageId' => $model->id]) ?>"
                   class="btn btn-xs btn-success catalog-item-set-img"
                   style="position:absolute; top:5px; left:5px; margin:0;"
                   data-toggle="tooltip"
                   title="Установить изображение">
                    <i class="fa fa-sm  fa-heart-o"></i>
                </a>
                <a href="<?= Url::to(['/item/remove-image', 'imageId' => $model->id]) ?>"
                   class="btn btn-xs btn-danger catalog-item-remove-img"
                   style="position:absolute; top:5px; right:5px; margin:0;"
                   data-toggle="tooltip"
                   title="Удалить изображение">
                    <i class="fa fa-sm fa-times"></i>
                </a>

            <?php endif ?>
        </div>

    <div class="clearfix"></div>


        <div class="row">
            <hr style="margin: 10px 0;"/>
        </div>


<?php endif ?>