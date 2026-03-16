<?php

namespace app\controllers;

use Yii;
use app\models\Course;
use app\models\CouseSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

class CourseController extends Controller
{
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'access' => [
                    'class' => AccessControl::class,
                    'rules' => [
                        [
                            'allow' => true,
                            'roles' => ['@'],
                        ],
                    ],
                ],
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    private function isAdmin(): bool
    {
        return !Yii::$app->user->isGuest &&
            Yii::$app->user->identity->user_role === 'admin';
    }

    public function actionIndex()
    {
        $searchModel = new CouseSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
            'isAdmin'      => $this->isAdmin(),
        ]);
    }

    public function actionView($id)
    {
        return $this->render('view', ['model' => $this->findModel($id)]);
    }

    public function actionCreate()
    {
        if (!$this->isAdmin()) {
            throw new ForbiddenHttpException('Only admins can create subjects.');
        }

        $model = new Course();
        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['index']);
            }
        }
        return $this->redirect(['index']);
    }

    public function actionUpdate($id)
    {
        if (!$this->isAdmin()) {
            throw new ForbiddenHttpException('Only admins can edit subjects.');
        }

        $model = $this->findModel($id);
        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }
        return $this->redirect(['index']);
    }

    public function actionDelete($id)
    {
        if (!$this->isAdmin()) {
            throw new ForbiddenHttpException('Only admins can delete subjects.');
        }

        $this->findModel($id)->delete();
        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = Course::findOne(['id' => $id])) !== null) {
            return $model;
        }
        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}