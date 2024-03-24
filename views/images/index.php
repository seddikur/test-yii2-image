<?php

use app\models\Images;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use branchonline\lightbox\Lightbox;
use app\helpers\ImageHelper;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */


$this->title = 'Картинки';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="images-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Добавить картинку', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php
    echo '<p> Галерея </p>';
    foreach ($dataProvider->getModels() as $images) {
        echo Lightbox::widget([
            'files' => [
                [
                    'thumb' => $images->getThumb(),
                    'original' => $images->getImagePath(),
                    'title' => $images->filename,
                ],
            ]
        ]);

    }
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'pager' => [
            'class' => 'yii\bootstrap5\LinkPager'
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            [
                'header' => '<i class="bi bi-card-image"></i>',
                'options' => ['width' => 40],
                'content' => function ($model) {
                    if (!empty($model['filename'])) {
                        $imgContent = Html::tag('div', Html::img(Images::makePath($model['filename']), [
                            'width' => '40px',
                            'height' => '40px',
                        ]), [
//                            'style' => "height: 40px !important; width: 40px !important",
                            'class' => 'thumbnail-sm pull-left'
                        ]);
                    } else {
                        $imgContent = Html::tag('span', '', [
                            'class' => 'bi bi-card-image'
                        ]);
                    }

                    return Html::tag('span', $imgContent, [
                        'class' => 'image-wrapper'
                    ]);
                },
                'format' => 'raw'
            ],
            'filename',

            [
                'header' => 'Скачать',
                'format' => 'raw',
                'value' => function ($model) {
                    return Html::a(
                        '<i class="bi bi-arrow-down-circle"></i>',
//                        ['download', 'file'=>$model->filename],
                        ['file', 'filename'=>$model->filename],
                        ['target' => '_blank']
                    );
                }
            ],
            [
                'header' => 'Скачать (.zip)',
                'format' => 'raw',
                'value' => function ($model) {
                    return Html::a(
                        '<i class="bi bi-file-earmark-zip"></i>',
                        ['zip', 'filename'=>$model->filename],
                        ['target' => '_blank']
                    );
                }
            ],
            [
                'label' => 'Расширение',
                'format' => 'raw',
                'value' => function ($model) {
                    return ImageHelper::extension($model->filename);
                }
            ],
            [
                'label' => 'Размер',
                'format' => 'raw',
                'value' => function ($model) {
                    return ImageHelper::size($model->filename);
                }
            ],
            'img_url',
            'created_at:datetime',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Images $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                }
            ],
        ],
    ]); ?>



</div>
