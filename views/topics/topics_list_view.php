<?php

/*File used to display child categories based kt173*/
use yii\helpers\Url;
use app\vendor\KTComponents\Admin;
use \yii\helpers\Html;

?>

<div class=" col-md-4 ">
    <?php
    $boxClassNameList = Admin::getBoxClass();
    $class = Admin::getClassName($key);
    $className = $boxClassNameList[$class];
    $mainModel = $model;
    $model = $model['topicsInfo'][0];
    $topQuestionList = json_decode(Admin::getTopQuestions($model['topic_id']), true);

    $topicUrl = Url::toRoute(['topics/get-topic-questions', 'slug' => $mainModel['slug']]);

    ?>
    <div class="box <?= $className ?>">
        <div class="box-header with-border">

            <h3 class="box-title">
                <strong>
			<a href="<?= $topicUrl ?>" title="<?= Yii::t('app', 'Question Name') ?>"><?= strip_tags($model['topic_name']) ?></a>
                </strong>
            </h3>

        </div>
        <!-- /.box-header -->
        <div class="box-body category-section inner-box">
            <?php
            $topQuestionList = json_decode(Admin::getTopQuestions($model['topic_id']), true);
            if ($topQuestionList) {
                echo '<ul>';
                foreach ($topQuestionList as $question) {
                    $questionMainModel = $question;
                    $question = $question['questionsInfo'][0];
                    $questionUrl = Url::toRoute(['topics/get-question-info', 'topicslug' => $mainModel['slug'],'slug' => $questionMainModel['slug']]);

                    if ($question) {
                        ?>
                        <li>
                            <a href="<?= $questionUrl ?>"
                               title="<?= Yii::t('app', 'Question Name') ?>"><?= strip_tags($question['question_name']) ?></a>
                        </li>
                    <?php
                    }
                }
                echo '</ul>';
            } else {
                $link = (Yii::$app->user->IsGuest) ? '' : '<a href="javascript:void(0);" onclick="createQuestion(' . $model['topic_id'] . ')"> ' . Yii::t('app', 'Create a new question') . '</a>';
                echo '<div class="no-questions" style="color: #f39c12">
                    ' . Yii::t('app', 'Questions not available') . '
                    <p>' . $link . '
                    </div>';
            }

            ?>
        </div>
        <!-- /.box-body -->
    </div>
    <!-- /.box -->
</div>
