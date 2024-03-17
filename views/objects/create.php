<?php

use yii\helpers\Html;

/**
 * @var $upload \app\models\Images
 * @var app\models\Objects $model
 * @var yii\web\View $this
 */

$this->title = 'Create Objects';
$this->params['breadcrumbs'][] = ['label' => 'Objects', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="objects-create">


    <?= $this->render('_form', [
        'model' => $model,
        'upload' => $upload,
    ]) ?>

</div>
