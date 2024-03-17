<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Images $model */

$this->title = 'Update Images: ' . $model->filename;
$this->params['breadcrumbs'][] = ['label' => 'Images', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="images-update">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
