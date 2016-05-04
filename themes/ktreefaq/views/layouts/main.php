<?php
//require(__DIR__ . '/../../ThemeAssets.php');
/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use app\themes\ktreefaq\ThemeAssets;
use yii\helpers\Url;

//header('X-Frame-Options: SAMEORIGIN');
ThemeAssets::register($this);
$adminSettings=Yii::$app->getSettings();
$this->title = ($this->title) ? $this->title : Yii::t('app',$adminSettings['site_name']);

if (Yii::$app->controller->action->id === 'login') {
    /**
     * Do not use this code in your template. Remove it.
     * Instead, use the code  $this->layout = '//main-login'; in your controller.
     */
    echo $this->render(
        'main-login',
        ['content' => $content]
    );
} else {


     $directoryAsset = Yii::$app->assetManager->getPublishedUrl('@bgtheme/weadminlte');

   ?>
	
	<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>"/>
		<?php if($adminSettings['searchengine_hide'] == 1){?>
			<meta name="robots" content="noindex"> 
		<?php }?>
        <meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="<?php echo $adminSettings['meta_keywords']; ?>" content="<?php echo $adminSettings['meta_title'];?>"/>
		<meta name="<?php echo $adminSettings['meta_keywords']; ?>" content="<?php echo $adminSettings['meta_description'];?>"/>	
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body class="hold-transition skin-blue layout-top-nav">
    <?php $this->beginBody() ?>
    <div class="wrapper">
        <?= $this->render(
            'header.php',
            ['directoryAsset' => $directoryAsset]
        ) ?>

        <?= $this->render(
            'content.php',
            ['content' => $content, 'directoryAsset' => $directoryAsset,'adminSettings'=>$adminSettings]
        ) ?>
        <footer class="main-footer">
            <div class="pull-right hidden-xs">
                <?= Yii::getAlias('@poweredby'); ?>
            </div>
            <?= Yii::t('app',$adminSettings['footer_content']) ?>
        </footer>
    </div>

    <?php $this->endBody() ?>
    </body>
    </html>
    <?php $this->endPage() ?>
<?php } ?>
<script type="text/javascript">//Url to use globally

    var current_user = "<?php echo Yii::$app->user->id; ?>"; 
    var loginUrl = "<?php echo Url::toRoute('site/login',true); ?>";
    var language = "<?php echo Yii::$app->language; ?>";

</script>
