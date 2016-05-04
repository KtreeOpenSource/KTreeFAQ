<?php
namespace app\modules\admin;

use Yii;
use yii\web\View;
use yii\base\Application;

use app\modules\admin\behaviours\CacheFlush;
use app\modules\admin\behaviours\SortableModel;

class AdminModule extends \yii\base\Module
{
    const VERSION = 0.9;

    public $settings;
    public $activeModules;

    private $_installed;

    public function behaviors()
    {
        return [
            CacheFlush::className(),
            SortableModel::className()
        ];
    }

    public function getInstalled()
    {
        if($this->_installed === null) {
            try {
			     Yii::$app->db->open();
                $this->_installed = Yii::$app->db->createCommand("SHOW TABLES LIKE '".Yii::$app->db->tablePrefix."%'")->query()->count() > 0 ? true : false;
            } catch (\Exception $e) {
                $this->_installed = false;
            }
        }
        return $this->_installed;
    }
}
