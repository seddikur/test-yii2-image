<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Objects $model */

$this->title = 'Update Objects: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Objects', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="objects-update">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
