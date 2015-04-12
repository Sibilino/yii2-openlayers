<?php
namespace sibilino\yii2\openlayers;

use yiiunit\TestCase;
use yii\helpers\Json;

class OLTest extends TestCase
{
	protected function setUp()
	{
		parent::setUp();
		$this->mockApplication([
				'vendorPath' => __DIR__.'/../../../..',
				'components' => [
						'assetManager' => [
								'basePath' => __DIR__.'/../../../../../assets',
								'baseUrl' => 'http://localhost/tester2/assets',
						],
				],
				'aliases' => [
						'@sibilino/yii2/openlayers' => __DIR__.'/../widget',
				],
		]);
	}
	
	public function testEncode()
	{
		$config = new OL('layer.Test', [
			'view' => 'testview',
			'source' => new OL('test.source.WTF', [
				'foo' => 'bar',
			])
		]);
		$this->assertEquals('new ol.layer.Test({"view":"testview","source":new ol.test.source.WTF({"foo":"bar"})})', Json::encode($config));
	}
}