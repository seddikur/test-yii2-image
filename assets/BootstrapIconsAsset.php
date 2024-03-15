<?php

namespace app\assets;

class BootstrapIconsAsset extends \yii\web\AssetBundle
{
    /**
     * Набор ресурсов иконок bootstrap 5
     * установка composer require npm-asset/bootstrap-icons
     */

    public $sourcePath = '@npm/bootstrap-icons';
    public $css = ['font/bootstrap-icons.css'];
}