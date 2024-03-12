<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Images $model */

$this->title = 'Create Images';
$this->params['breadcrumbs'][] = ['label' => 'Images', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="images-create">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
