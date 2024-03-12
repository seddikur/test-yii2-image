<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;

/** @var yii\web\View $this */
/** @var app\models\Images $model */
/** @var app\models\Images $upload */
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

<div class="catalog-img-gallery">
    <?php if ($model->scenario == 'copy' && !empty($model->images)) : ?>
        <div class="alert bg-warning text-warning">
            <i class="fa fa-exclamation-triangle text-warning"></i> При <b>сохранении</b> будут скопированы изображения
        </div>
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

    <nav>
        <div class="nav nav-tabs" id="nav-tab" role="tablist">
            <button class="nav-link active" id="device-tab" data-bs-toggle="tab" data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home" aria-selected="true">Загрузка с устройства</button>
            <button class="nav-link" id="src-tab" data-bs-toggle="tab" data-bs-target="#nav-profile" type="button" role="tab" aria-controls="nav-profile" aria-selected="false">Загрузка по ссылке</button>
        </div>
    </nav>
    <div class="tab-content" id="nav-tabContent">
        <div class="tab-pane fade show active" id="device" role="tabpanel" aria-labelledby="nav-home-tab" tabindex="0">
            <!-- START Форма для загрузки изображений -->
            <?= $form->field($model, 'images[]')->widget(FileInput::class, [
                'options' => [
                    'accept' => ['image/png', 'image/jpeg'],
                    'multiple' => true,
                ],
                'pluginOptions' => [
                    'showPreview' => true,
                    'showCaption' => true,
                    'showRemove' => true,
                    'showUpload' => true,
                    'removeClass' => 'btn btn-sm btn-default',
                    'removeIcon' => '<i class="fa fa-times-circle"></i> ',
                    'browseIcon' => '<i class="fa fa-plus"></i> Выбрать изображения',
                    'browseClass' => 'btn btn-github btn-sm',
                    'browseLabel' => '',
                    'previewFileType' => 'image',
                    'maxFileCount' => 20,
                ]
            ]); ?>
            <!-- END Форма для загрузки изображений -->
        </div>
        <div class="tab-pane fade" id="src" role="tabpanel" aria-labelledby="nav-profile-tab" tabindex="0">

            <div class="imageBlock">
                <div class="imageBlock__preview hide">
                    <img class="jsSetImg" src="" alt="">
                    <div class="imageBlock__preview-btn">
                        <button type="button" class="jsDeleteImg">
                            X
                        </button>
                        <button type="button" class="jsModalPreviewOpen">
                            <i class="glyphicon glyphicon-zoom-in"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div style="display: flex;">
                <?= Html::input('text', 'img_url', '', ['placeholder' => "Введите ссылку на изображение", 'class' => 'form-control jsImgUrl']) ?>
                <?= Html::hiddenInput('current_img_url', '', ['class' => 'current_img_url']) ?>
                <?= Html::button('Загрузить', ['class' => 'form-control btn btn-success jsUploadImg', 'style' => 'width: 120px']) ?>
            </div>
        </div>
    </div>


<ul class="nav nav-tabs" style="display: flex; text-align: center; margin-bottom: 15px;">
    <li class="active" style="position: relative">
        <a class="jsDisableDevise" data-toggle="tab" href="#device" style="position: relative; z-index: 2;">Загрузка с устройства</a>
        <span class="showImageAlert" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: 1"></span>
    </li>
    <li style="position: relative">
        <a class="jsDisableUrl" data-toggle="tab" href="#src" style="position: relative; z-index: 2;">Загрузка по ссылке</a>
        <span class="showImageAlert" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: 1"></span>
    </li>
</ul>

<div class="category-upload-block image-upload <? //= count($model->filename) >= 5 ? 'hidden' : '' ?>">
    <div class="tab-content">
        <div id="device" class="tab-pane fade in active">
                <!-- START Форма для загрузки изображений -->
                <?= $form->field($model, 'images[]')->widget(FileInput::class, [
                    'options' => [
                        'accept' => ['image/png', 'image/jpeg'],
                        'multiple' => true,
                    ],
                    'pluginOptions' => [
                        'showPreview' => true,
                        'showCaption' => true,
                        'showRemove' => true,
                        'showUpload' => true,
                        'removeClass' => 'btn btn-sm btn-default',
                        'removeIcon' => '<i class="fa fa-times-circle"></i> ',
                        'browseIcon' => '<i class="fa fa-plus"></i> Выбрать изображения',
                        'browseClass' => 'btn btn-github btn-sm',
                        'browseLabel' => '',
                        'previewFileType' => 'image',
                        'maxFileCount' => 20,
                    ]
                ]); ?>
                <!-- END Форма для загрузки изображений -->

        </div>
        <div id="src" class="tab-pane fade">

                <div class="imageBlock">
                    <div class="imageBlock__preview hide">
                        <img class="jsSetImg" src="" alt="">
                        <div class="imageBlock__preview-btn">
                            <button type="button" class="jsDeleteImg">
                                X
                            </button>
                            <button type="button" class="jsModalPreviewOpen">
                                <i class="glyphicon glyphicon-zoom-in"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div style="display: flex;">
                    <?= Html::input('text', 'img_url', '', ['placeholder' => "Введите ссылку на изображение", 'class' => 'form-control jsImgUrl']) ?>
                    <?= Html::hiddenInput('current_img_url', '', ['class' => 'current_img_url']) ?>
                    <?= Html::button('Загрузить', ['class' => 'form-control btn btn-success jsUploadImg', 'style' => 'width: 120px']) ?>
                </div>

        </div>
    </div>
</div>
    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>
<?php ActiveForm::end();?>