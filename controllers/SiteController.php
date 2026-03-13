<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\RegisterForm;
use app\models\ContactForm;

class SiteController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $loginModel    = new LoginForm();
        $registerModel = new RegisterForm();

        // Handle login
        if (Yii::$app->request->isPost && Yii::$app->request->post('action') === 'login') {
            if ($loginModel->load(Yii::$app->request->post()) && $loginModel->login()) {
                return $this->goBack();
            }
            $loginModel->password = '';
        }

        // Handle register
        if (Yii::$app->request->isPost && Yii::$app->request->post('action') === 'register') {
            if ($registerModel->load(Yii::$app->request->post()) && $registerModel->register()) {
                Yii::$app->session->setFlash('success', 'Account created! You can now log in.');
                return $this->redirect(['login']);
            }
        }

        return $this->render('login', [
            'loginModel'    => $loginModel,
            'registerModel' => $registerModel,
        ]);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->goHome();
    }

    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');
            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    public function actionAbout()
    {
        return $this->render('about');
    }
}