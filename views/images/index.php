<?php

use app\models\Images;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use branchonline\lightbox\Lightbox;

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
    foreach ($dataProvider->getModels() as $images) {
        echo Lightbox::widget([
            'files' => [
                [
                    'thumb' => $images->img_url . '/thumbs/' . $images->filename,
                    'original' => $images->img_url . '/' . $images->filename,
                    'linkOptions' => [
                        'data-lightbox' => $images->img_url . $images->id,
                    ],
                    'title' => $images->filename,
                ],
            ]
        ]);

    }
    ?>
    <?

    ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'filename',
            'img_url',
            'created_at:date',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Images $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                }
            ],
        ],
    ]); ?>


</div>
