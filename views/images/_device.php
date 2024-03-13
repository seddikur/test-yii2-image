<?php

use kartik\file\FileInput;

/** @var app\models\Images $model */
/** @var yii\widgets\ActiveForm $form */
?>
 <!-- START Форма для загрузки изображений -->
            <?= $form->field($model, 'images[]')->widget(FileInput::class, [
                'options' => [
                    'accept' => ['image/png', 'image/jpeg'],
                    'multiple'=>true
                ],
                'pluginOptions' => [
                    'showPreview' => true,
                    'showCaption' => true,
                    'showRemove' => true,
                    'showUpload' => true,
                    'removeClass' => 'btn btn-sm btn-default',
//                    'removeIcon' => '<i class="bi bi-times-circle"></i> ',
                    'browseIcon' => 'Выбрать изображения',
                    'browseClass' => 'btn btn-github',
                    'browseLabel' => '',
                    'previewFileType' => 'image',
                    'maxFileCount' => 5,
                    'overwriteInitial'=>false,
                ]
            ]); ?>
<!-- END Форма для загрузки изображений -->