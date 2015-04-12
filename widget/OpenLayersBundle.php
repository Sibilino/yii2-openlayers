<?php
namespace sibilino\yii2\openlayers;

use yii\web\AssetBundle;
use yii\web\View;

class OpenLayersBundle extends AssetBundle 
{
	public $sourcePath = __DIR__;
	
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