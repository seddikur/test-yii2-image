<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Objects $model */

$this->title = 'Create Objects';
$this->params['breadcrumbs'][] = ['label' => 'Objects', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="objects-create">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
