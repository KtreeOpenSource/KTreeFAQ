<?php
namespace app\vendor\KTComponents\assets;
use yii;
use yii\base\Exception;
use yii\web\AssetBundle;

/**
 * AdminLte AssetBundle
 * @since 0.1
 */


class AppAsset extends AssetBundle
{
   public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset'

    ];
}
