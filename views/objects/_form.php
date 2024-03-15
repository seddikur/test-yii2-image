<?php

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Images $model */
/** @var yii\widgets\ActiveForm $form */

$form = ActiveForm::begin([
//    'enableClientValidation' => false,
//    'enableAjaxValidation' => true,
    'options' => [
        'enctype' => 'multipart/form-data',
        'class' => 'form-group-sm',
        'id' => 'catalog-item-form'
    ]
]);

?>
    <div class="row">
        <div class="col-lg-6 col-md-12">
            <div class="box box-default">
                <div class="box-header with-border">
                    <h4 class="box-title">
                        <i class="fa fa-align-left text-muted"></i> Основное
                    </h4>
                </div>
                <div class="box-body">
                    <?= $form->field($model, 'title')->textInput() ?>
                </div>
            </div>
        </div>

        <div class="col-lg-6 col-md-12">
            <div class="box box-default">
                <div class="box-header with-border">
                    <h4 class="box-title">
                        <i class="fa fa-align-left text-muted"></i> Картинка
                    </h4>
                </div>
                <div class="box-body">
                    <div class="catalog-img-gallery">
                        <?php if (!empty($model->images)) : ?>

                            <div class="row">
                                <div class="col-lg-12">
                                    <?php foreach ($model->images as $img) : ?>
                                        <div class="thumbnail-sm pull-left">
                                            <img src="<?= $img->thumb ?>"/>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            <div class="row">
                                <hr/>
                            </div>
                        <?php else : ?>
                            <!-- START Галерея изображений Lightbox -->
                            <?= $this->render('_gallery', [
                                'model' => $model
                            ]) ?>
                            <!-- END Галерея изображений Lightbox -->
                        <?php endif ?>
                    </div>

                    <?= $this->render('_imgmodal', [
                        'model' => $model
                    ]) ?>

                    <div class="alert alert-warning " role="alert">
                        Одновременно использовать <b>2 способа сохранения</b> изображения нельзя!
                    </div>

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


                </div>
            </div>
        </div>

    </div>


    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>
<?php ActiveForm::end(); ?>