<?php
namespace sibilino\yii2\openlayers;

use yii\web\AssetBundle;
use yii\web\View;

class OpenLayersBundle extends AssetBundle 
{
	public $sourcePath = '@vendor/sibilino/yii2/openlayers/widget';
	
	public $js = [
		'js/ol.js',
	];
	public $jsOptions = [
		'position' => View::POS_HEAD,
	];
	
	public $css = [
		'css/ol.css',
	];
}