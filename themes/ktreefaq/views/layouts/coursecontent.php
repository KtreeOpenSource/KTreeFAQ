<?php
use yii\widgets\Breadcrumbs;
use app\vendor\KTComponents\Alert;
use pendalf89\filemanager\widgets\FileInput;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;

?>
<div class="content-wrapper two-column">

    <?php
    if ($this->title !== null) {
        echo '<section class="content-header">';
        echo Breadcrumbs::widget(
            [
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]
        );
        echo '</section>';

    }
    ?>

<div class="media-model" style="display:none">
  <?php
        echo FileInput::widget([
            'name' => 'mediafile',
            'id'=>'mediainput',
            'buttonTag' => 'button',
            'buttonName' => Yii::t('app','Browse'),
            'buttonOptions' => ['class' => 'btn btn-default'],
            'options' => ['class' => 'form-control'],
            'template' => '<div class="input-group">{input}<span class="input-group-btn">{button}</span></div>',
            'thumb' => 'original',
            'imageContainer' => '.img',
            'pasteData' => FileInput::DATA_URL,
            'callbackBeforeInsert' => 'function(e, data) {
		$(".filemanager-modal").modal("hide");                
                $formUrl = $(this).closest("form").attr("action");
                button.trigger("mediaReceieved", baseUrl+"/"+data.url);
        }',
        ]);
        ?>
    </div>
    <section class="content course-view">
        <?= Alert::widget() ?>
        <?= $content ?>
    </section>
</div>

<div class="go-up" style="right: 20px;display:none;">
    <i class="fa fa-chevron-up"></i>
</div>

<!-- Add the sidebar's background. This div must be placed
     immediately after the control sidebar -->
<div class='control-sidebar-bg'></div>
