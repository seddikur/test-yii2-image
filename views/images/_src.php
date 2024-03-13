<?php
use yii\helpers\Html;

/** @var app\models\Images $model */
/** @var yii\widgets\ActiveForm $form */
?>


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