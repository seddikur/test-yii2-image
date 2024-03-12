<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Images $model */

$this->title = 'Update Images: ' . $model->filename;
$this->params['breadcrumbs'][] = ['label' => 'Images', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->filename, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="images-update">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
