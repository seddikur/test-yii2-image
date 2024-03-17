<?php

use kartik\file\FileInput;

/** @var app\models\Images $model */
/** @var yii\widgets\ActiveForm $form */
?>
<style>
    .help-block{

        width: 100%;
        margin-top: 0.25rem;
        font-size: 0.875em;
        color: var(--bs-form-invalid-color);

    }
</style>
 <!-- START Форма для загрузки изображений -->

            <?= $form->field($model, 'imageFile[]')->widget(FileInput::class, [
                'options' => [
                    'accept' => ['image/*'],
                    'multiple'=>true
                ],
                'pluginOptions' => [
                    'showPreview' => true,
                    'showCaption' => true,
                    'showRemove' => true,
                    'showUpload' => true,
                    'removeClass' => 'btn btn-sm btn-default',
                    'removeIcon' => '<i class="bi bi-info-circle"></i> ',
                    'browseIcon' => 'Выбрать изображения',
                    'browseClass' => 'btn btn-primary',
                    'browseLabel' => '',
                    'previewFileType' => 'image',
                    'maxFileCount' => 5,
                    'overwriteInitial'=>false,
                ]
            ]); ?>
<!-- END Форма для загрузки изображений -->