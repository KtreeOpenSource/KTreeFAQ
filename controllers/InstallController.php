<?php

namespace app\controllers;

use Yii;
use app\modules\admin\models\InstallForm;
use yii\easyii\models\Photo;
use yii\easyii\models\SeoText;
use yii\easyii\modules\carousel\models\Carousel;
use yii\easyii\modules\catalog;
use yii\easyii\modules\article;
use yii\easyii\modules\faq\models\Faq;
use yii\easyii\modules\file\models\File;
use yii\easyii\modules\gallery;
use yii\easyii\modules\guestbook\models\Guestbook;
use yii\easyii\modules\news\models\News;
use yii\easyii\modules\page\models\Page;
use yii\easyii\modules\text\models\Text;

/*
 * Controller extends web controller for installation set up, to create database.
 */
class InstallController extends \yii\web\Controller
{
    public $layout = 'install';
    public $defaultAction = 'step1';
    public $db;

    public $dbConnected = false;
    public $adminInstalled = false;
    public $shopInstalled = false;

    public function init()
    {
        parent::init();

        $this->db = Yii::$app->db;
        try {
            Yii::$app->db->open();
            $this->dbConnected = true;
            $this->adminInstalled = Yii::$app->getModule('admin')->installed;
            if($this->adminInstalled) {
               // $this->shopInstalled = Page::find()->count() > 0 ? true : false;
            }
        }
        catch(\Exception $e){
            $this->dbConnected = false;
        }
    }

    /**
     * Returns to installation Step one screen
     * @return string|\yii\web\Response
     */
    public function actionStep1()
    {
        if($this->adminInstalled){
            return $this->redirect($this->shopInstalled ? ['/'] : ['/install/step3']);
        }
        return $this->render('step1');
    }

    /**
     * Returns to installation step two screen
     * @return string|\yii\web\Response
     */
    public function actionStep2()
    {

        if($this->adminInstalled){
            return $this->redirect($this->shopInstalled ? ['/'] : ['/install/step3']);
        }
        
        $installForm = new InstallForm();
        return $this->render('step2', ['model' => $installForm]);
    }


    /**
     * Returns to installation step two screen
     * @return string
     */
    public function actionStep3()
    {
        $result = [];
        return $this->render('step3', ['result' => $result]);
    }

    private function registerI18n()
    {
        Yii::$app->i18n->translations['easyii/install'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en-US',
            'basePath' => '@easyii/messages',
            'fileMap' => [
                'easyii/install' => 'install.php',
            ]
        ];
    }

   /*public function actionRefreshDataBase(){
	
	try{

Yii::$app->db->createCommand()->dropTable($table);  
		Yii::$app->db->createCommand("SET foreign_key_checks = 0")->execute();
		$tables = Yii::$app->db->schema->getTableNames();  

		foreach ($tables as $table) {  
		    $result = Yii::$app->db->createCommand()->dropTable($table);  
echo '<pre>';		    	
print_r($result);
		}  die;
		Yii::$app->db->createCommand("SET foreign_key_checks = 1")->execute();	
	
		 if (!Yii::$app->getModule('admin')->installed) {
		    return $this->redirect(['install/step1']);
		} 
	
	}catch(Exception $e){
	
		print_r($e->getMessage);die;
	}
   }*/

}
