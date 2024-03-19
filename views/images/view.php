<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\Images;

/** @var yii\web\View $this */
/** @var app\models\Images $model */

$this->title = $model->filename;
$this->params['breadcrumbs'][] = ['label' => 'Images', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="images-view">


    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'filename',
            [
                'attribute'=>'photo',
                'value'=> Images::makePath($model->filename),
                'format' => ['image',['width'=>'200','height'=>'200']],
            ],
            'created_at',
        ],
    ]) ?>

</div>
