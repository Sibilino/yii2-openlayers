<?php
namespace sibilino\yii2\openlayers;

use yii\web\AssetBundle;

class ModuleBundle extends AssetBundle
{
    public $sourcePath = __DIR__;

    public $js = [
        'js/module.js',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}