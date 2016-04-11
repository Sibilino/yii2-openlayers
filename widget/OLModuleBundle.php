<?php
namespace sibilino\yii2\openlayers;

use yii\web\AssetBundle;

class OLModuleBundle extends AssetBundle
{
    public $sourcePath = __DIR__;

    public $js = [
        'js/olwidget.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
    ];
}
