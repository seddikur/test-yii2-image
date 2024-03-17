<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Images $model */
/** @var yii\widgets\ActiveForm $form */

$form = ActiveForm::begin([
    'enableClientValidation' => true,
//    'enableAjaxValidation' => true,
    'options' => [
        'enctype' => 'multipart/form-data',
//        'class' => 'form-group-sm',
//        'id' => 'catalog-item-form'
    ]
]);

?>

    <div class="img-gallery">
        <?php if (!$model->isNewRecord) : ?>
            <img src="<?= $model->getImagePath() ?>" alt="" width="300">

            <a href="<?= \yii\helpers\Url::to(['/image/remove-image', 'imageId' => $model->id]) ?>"
               class="btn btn-xs btn-danger catalog-item-remove-img"
               style="
                   position:relative;
                   top:-57px;
                   right:55px;
                    margin:0;"
               data-toggle="tooltip"
               title="Удалить изображение">
                <i class="bi bi-x"></i>
            </a>
        <?php endif ?>
    </div>


    <!--    <div class="alert alert-warning " role="alert">-->
    <!--        Одновременно использовать <b>2 способа сохранения</b> изображения нельзя!-->
    <!--    </div>-->

<?php
echo \yii\bootstrap5\Tabs::widget([
    'items' => [
        [
            'label' => 'Загрузка с устройства',
            'content' => $this->render('_device', ['model' => $model, 'form' => $form]),
            'active' => true
        ],
        [
            'label' => 'Загрузка по ссылке',
            'content' => $this->render('_src', ['model' => $model, 'form' => $form]),
            'disabled' => true
        ],

    ],
]);
?>


    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>
<?php ActiveForm::end(); ?>