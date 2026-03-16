<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;

/** @var app\models\Teacher $model */

echo "<pre>"; print_r($model->courses); echo "</pre>";
?>

<div class="card h-100">
    <div class="card-body">
        <h5 class="card-title"><?= Html::encode($model->teacher_name) ?></h5>
        <hr>
        <p class="card-text">
            <strong>Courses:</strong><br>
            <?= Html::encode(implode(', ', ArrayHelper::getColumn($model->courses, 'course_name'))) ?: 'No courses assigned' ?>
        </p>
        <p class="card-text">
            <strong>Active:</strong>
            <?= $model->is_active ? '<span class="badge bg-success">Yes</span>' : '<span class="badge bg-secondary">No</span>' ?>
        </p>
    </div>
    <div class="card-footer bg-transparent">
        <?= Html::a(Yii::t('app', 'View Details'), ['view', 'id' => $model->id], ['class' => 'btn btn-outline-primary btn-sm']) ?>
    </div>
</div>