<?php

/** @var yii\web\View $this */

$this->title = 'Dashboard';

$user = Yii::$app->user->identity;
$username = $user ? $user->username : 'Guest';
$role = $user ? $user->user_role : '';
?>

<div class="dashboard-page">

    <div class="dashboard-hero">
        <h1 class="dashboard-hero__title">Welcome, <?= htmlspecialchars($username) ?>!</h1>
        <p class="dashboard-hero__sub">You're logged in as <span class="dashboard-role"><?= htmlspecialchars($role) ?></span></p>
    </div>

    <p class="dashboard-section-label">Here are some things to do:</p>

    <div class="dashboard-grid">

        <!-- Assignments -->
        <a href="/assignment" class="dashboard-card">
            <div class="dashboard-card__icon">📋</div>
            <div class="dashboard-card__name">Assignments</div>
            <div class="dashboard-card__desc">Create, edit and view assignments for your subjects.</div>
        </a>

        <!-- Teachers -->
        <a href="/teacher" class="dashboard-card">
            <div class="dashboard-card__icon">👨‍🏫</div>
            <div class="dashboard-card__name">Teachers</div>
            <div class="dashboard-card__desc">View a list of all teachers and the subjects they're teaching.</div>
        </a>

        <!-- Subjects / Courses -->
        <a href="/course" class="dashboard-card">
            <div class="dashboard-card__icon">📚</div>
            <div class="dashboard-card__name">Subjects</div>
            <div class="dashboard-card__desc">Here's an overview of all subjects.</div>
        </a>

    </div>

</div>