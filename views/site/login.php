<?php

/** @var yii\web\View $this */
/** @var app\models\LoginForm $loginModel */
/** @var app\models\RegisterForm $registerModel */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Login';

// Open register modal if there were register errors
$openRegister = $registerModel->hasErrors() ? 'true' : 'false';
?>

<div class="auth-page">

    <!-- Flash success message -->
    <?php if (Yii::$app->session->hasFlash('success')): ?>
        <div class="auth-flash">
            <?= Yii::$app->session->getFlash('success') ?>
        </div>
    <?php endif; ?>

    <div class="auth-hero">
        <div class="auth-hero__icon">📚</div>
        <h1 class="auth-hero__title">Welcome back!</h1>
        <p class="auth-hero__sub">Log in to manage your assignments, teachers and subjects.</p>

        <div class="auth-hero__actions">
            <button class="btn-auth-primary" data-bs-toggle="modal" data-bs-target="#loginModal">
                Log in
            </button>
            <button class="btn-auth-secondary" data-bs-toggle="modal" data-bs-target="#registerModal">
                Create account
            </button>
        </div>

        <?php if ($loginModel->hasErrors()): ?>
            <div class="auth-error">
                <?php foreach ($loginModel->errors as $errors): ?>
                    <?php foreach ($errors as $error): ?>
                        <span><?= Html::encode($error) ?></span>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

</div>

<!-- ── LOGIN MODAL ───────────────────────────────────────────── -->
<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="loginModalLabel">Login</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <?php $form = \yii\bootstrap5\ActiveForm::begin([
                    'action' => Url::to(['site/login']),
                    'method' => 'post',
                    'options' => ['id' => 'login-form'],
            ]); ?>

            <?= Html::hiddenInput('action', 'login') ?>

            <div class="modal-body d-flex flex-column gap-3">

                <div>
                    <label class="form-label" for="loginform-username">Username</label>
                    <?= $form->field($loginModel, 'username', [
                            'template'      => '{input}{error}',
                            'errorOptions'  => ['class' => 'auth-field-error'],
                    ])->textInput(['class' => 'form-control', 'placeholder' => 'Your username', 'id' => 'loginform-username']) ?>
                </div>

                <div>
                    <label class="form-label" for="loginform-password">Password</label>
                    <?= $form->field($loginModel, 'password', [
                            'template'      => '{input}{error}',
                            'errorOptions'  => ['class' => 'auth-field-error'],
                    ])->passwordInput(['class' => 'form-control', 'placeholder' => '••••••••', 'id' => 'loginform-password']) ?>
                </div>

            </div>

            <div class="modal-footer d-flex justify-content-between align-items-center">
          <span class="auth-switch">
            No account?
            <a href="#" onclick="switchModal('loginModal','registerModal'); return false;">Register</a>
          </span>
                <?= Html::submitButton('Login', ['class' => 'btn-save']) ?>
            </div>

            <?php \yii\bootstrap5\ActiveForm::end(); ?>

        </div>
    </div>
</div>

<!-- ── REGISTER MODAL ────────────────────────────────────────── -->
<div class="modal fade" id="registerModal" tabindex="-1" aria-labelledby="registerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="registerModalLabel">Create a new Account</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <?php $regForm = \yii\bootstrap5\ActiveForm::begin([
                    'action' => Url::to(['site/login']),
                    'method' => 'post',
                    'options' => ['id' => 'register-form'],
            ]); ?>

            <?= Html::hiddenInput('action', 'register') ?>

            <div class="modal-body d-flex flex-column gap-3">

                <div>
                    <label class="form-label" for="registerform-username">Username</label>
                    <?= $regForm->field($registerModel, 'username', [
                            'template'      => '{input}{error}',
                            'errorOptions'  => ['class' => 'auth-field-error'],
                    ])->textInput(['class' => 'form-control', 'placeholder' => 'Choose a username', 'id' => 'registerform-username']) ?>
                </div>

                <div>
                    <label class="form-label" for="registerform-password">Password</label>
                    <?= $regForm->field($registerModel, 'password', [
                            'template'      => '{input}{error}',
                            'errorOptions'  => ['class' => 'auth-field-error'],
                    ])->passwordInput(['class' => 'form-control', 'placeholder' => '••••••••', 'id' => 'registerform-password']) ?>
                </div>

                <div>
                    <label class="form-label" for="registerform-confirmpassword">Confirm Password</label>
                    <?= $regForm->field($registerModel, 'confirmPassword', [
                            'template'      => '{input}{error}',
                            'errorOptions'  => ['class' => 'auth-field-error'],
                    ])->passwordInput(['class' => 'form-control', 'placeholder' => '••••••••', 'id' => 'registerform-confirmpassword']) ?>
                </div>

            </div>

            <div class="modal-footer d-flex justify-content-between align-items-center">
          <span class="auth-switch">
            Already have an account?
            <a href="#" onclick="switchModal('registerModal','loginModal'); return false;">Login</a>
          </span>
                <?= Html::submitButton('Register', ['class' => 'btn-save']) ?>
            </div>

            <?php \yii\bootstrap5\ActiveForm::end(); ?>

        </div>
    </div>
</div>

<script>
    function switchModal(hideId, showId) {
        const hide = bootstrap.Modal.getInstance(document.getElementById(hideId));
        if (hide) hide.hide();
        const show = new bootstrap.Modal(document.getElementById(showId));
        show.show();
    }

    // Auto-open register modal if it had errors
    document.addEventListener('DOMContentLoaded', function () {
        if (<?= $openRegister ?>) {
            new bootstrap.Modal(document.getElementById('registerModal')).show();
        } else if (<?= $loginModel->hasErrors() ? 'true' : 'false' ?>) {
            new bootstrap.Modal(document.getElementById('loginModal')).show();
        }
    });
</script>