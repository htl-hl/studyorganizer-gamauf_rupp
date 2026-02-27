<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Teacher_Course $model */

$this->title = Yii::t('app', 'Update Teacher Course: {name}', [
    'name' => $model->teacher_id,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Teacher Courses'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->teacher_id, 'url' => ['view', 'teacher_id' => $model->teacher_id, 'course_id' => $model->course_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="teacher--course-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
