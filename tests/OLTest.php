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
                'test' => new OL('test.Lol', new OL('test.nested', [
                    'why' => 'weCan',
                ]))
			])
		]);
		$this->assertEquals('new ol.layer.Test({"view":"testview","source":new ol.test.source.WTF({"foo":"bar","test":new ol.test.Lol(new ol.test.nested({"why":"weCan"}))})})', Json::encode($config));
	}
}