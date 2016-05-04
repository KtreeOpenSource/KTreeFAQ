<?php
namespace app\modules\admin\controllers;

use Yii;
use yii\web\ServerErrorHttpException;
use app\modules\admin\models\InstallForm;
use app\vendor\KTComponents\Admin;

class AdminController extends \yii\web\Controller
{
    public $layout = 'empty';

    /*public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {
            $this->registerI18n();
            return true;
        } else {
            return false;
        }
    }*/

    public function actionIndex()
    {

        $installForm = new InstallForm();

        if ($installForm->load(Yii::$app->request->post())) {

            $configFile = str_replace(Yii::getAlias('@webroot'), '', Yii::getAlias('@app')) . '/config/db.php';
            $dsn = "mysql:host=" . $installForm->host . ";dbname=" . $installForm->db_name;


            if (!$this->checkDbConnection($dsn, $installForm->db_username, $installForm->db_password)) {
                Yii::$app->getSession()->setFlash('error', 'We are unable to connect your database please provide valid db credentials');
                return $this->redirect(['install/step2']);
            } else {

                $installForm->tbl_prefix = ($installForm->tbl_prefix) ? ($installForm->tbl_prefix) : 'faq_';
                $demosql_file = Yii::getAlias('@app') . DIRECTORY_SEPARATOR . 'modules/admin/migrations/data.sql';
                $db_settings = "<?php
					return [
						'class' => 'yii\db\Connection',
						'dsn' => 'mysql:host=" . $installForm->host . ";dbname=" . $installForm->db_name . "',
						'username' => '" . $installForm->db_username . "',
						'password' => '" . $installForm->db_password . "',
						'charset' => 'utf8',
						'tablePrefix' => '" . $installForm->tbl_prefix . "',
						'enableSchemaCache' => true,
					];";
                file_put_contents($configFile, $db_settings);
                $demosql = file_get_contents($demosql_file);
                $demosql = str_replace('%faq_%', $installForm->tbl_prefix, $demosql);
                if (!$this->insertDemoData($dsn, $installForm->db_username, $installForm->db_password, $demosql)) {
                    Yii::$app->getSession()->setFlash('error', 'We are unable to write your database please check permissions');
                    return $this->redirect(['install/step2']);
                }

                        Admin::updateSettings($installForm);
                        return $this->redirect(['finish']);
            }

        } else {

            return $this->redirect(['install/step2']);
        }
    }

    public function actionFinish()
    {

        return $this->render('finish');
    }

    private function checkDbConnection($dsn, $username, $password)
    {
        try {
            $connection = new \yii\db\Connection([
                'dsn' => $dsn,
                'username' => $username,
                'password' => $password,
            ]);
            $connection->open();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    private function insertDemoData($dsn, $username, $password, $sql1)
    {
        try {
            $connection = new \yii\db\Connection([
                'dsn' => $dsn,
                'username' => $username,
                'password' => $password,
            ]);
            $connection->open();
            $transaction = $connection->beginTransaction();
            try {
                $connection->createCommand($sql1)->execute();
                $transaction->commit();

            } catch (\Exception $e) {
                $transaction->rollBack();
                return false;
            }
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    private function showError($text)
    {
        return $this->render('error', ['error' => $text]);
    }

    private function createUploadsDir()
    {
        $uploadsDir = Yii::getAlias('@webroot') . DIRECTORY_SEPARATOR . 'uploads';
        $uploadsDirExists = file_exists($uploadsDir);
        if (($uploadsDirExists && !is_writable($uploadsDir)) || (!$uploadsDirExists && !mkdir($uploadsDir, 0777))) {
            throw new ServerErrorHttpException('Cannot create uploads folder at `' . $uploadsDir . '` Please check write permissions.');
        }
    }

    private function insertSettings($installForm)
    {
        $db = Yii::$app->db;
        $password_salt = Yii::$app->security->generateRandomString();
        $root_auth_key = Yii::$app->security->generateRandomString();
        $root_password = sha1($installForm->root_password . $root_auth_key . $password_salt);

        $db->createCommand()->insert(Setting::tableName(), [
            'name' => 'recaptcha_key',
            'value' => $installForm->recaptcha_key,
            'title' => Yii::t('easyii/install', 'ReCaptcha key'),
            'visibility' => Setting::VISIBLE_ROOT
        ])->execute();

        $db->createCommand()->insert(Setting::tableName(), [
            'name' => 'password_salt',
            'value' => $password_salt,
            'title' => 'Password salt',
            'visibility' => Setting::VISIBLE_NONE
        ])->execute();

        $db->createCommand()->insert(Setting::tableName(), [
            'name' => 'root_auth_key',
            'value' => $root_auth_key,
            'title' => 'Root authorization key',
            'visibility' => Setting::VISIBLE_NONE
        ])->execute();

        $db->createCommand()->insert(Setting::tableName(), [
            'name' => 'root_password',
            'value' => $root_password,
            'title' => Yii::t('easyii/install', 'Root password'),
            'visibility' => Setting::VISIBLE_ROOT
        ])->execute();

        $db->createCommand()->insert(Setting::tableName(), [
            'name' => 'auth_time',
            'value' => 86400,
            'title' => Yii::t('easyii/install', 'Auth time'),
            'visibility' => Setting::VISIBLE_ROOT
        ])->execute();

        $db->createCommand()->insert(Setting::tableName(), [
            'name' => 'robot_email',
            'value' => $installForm->robot_email,
            'title' => Yii::t('easyii/install', 'Robot E-mail'),
            'visibility' => Setting::VISIBLE_ROOT
        ])->execute();

        $db->createCommand()->insert(Setting::tableName(), [
            'name' => 'admin_email',
            'value' => $installForm->admin_email,
            'title' => Yii::t('easyii/install', 'Admin E-mail'),
            'visibility' => Setting::VISIBLE_ALL
        ])->execute();

        $db->createCommand()->insert(Setting::tableName(), [
            'name' => 'recaptcha_secret',
            'value' => $installForm->recaptcha_secret,
            'title' => Yii::t('easyii/install', 'ReCaptcha secret'),
            'visibility' => Setting::VISIBLE_ROOT
        ])->execute();

        $db->createCommand()->insert(Setting::tableName(), [
            'name' => 'toolbar_position',
            'value' => 'top',
            'title' => Yii::t('easyii/install', 'Frontend toolbar position') . ' ("top" or "bottom")',
            'visibility' => Setting::VISIBLE_ROOT
        ])->execute();
    }

    private function installModules()
    {
        $language = Data::getLocale();
     }
}
