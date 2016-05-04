<?php
namespace app\modules\admin\assets;

class EmptyAsset extends \yii\web\AssetBundle
{
    public $sourcePath = '@app/modules/admin/media';
    public $css = [
        'css/empty.css',
    ];
    public $depends = [
        'yii\bootstrap\BootstrapAsset',
    ];
}
