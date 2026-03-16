<?php

namespace app\controllers;

use Yii;
use app\models\Teacher;
use app\models\TeacherSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

class TeacherController extends Controller
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
        $searchModel = new TeacherSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);
        $courses = \app\models\Course::find()->all();

        return $this->render('index', [
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
            'courses'      => $courses,
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
            throw new ForbiddenHttpException('Only admins can create teachers.');
        }

        $model = new Teacher();
        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                $tc = new \app\models\Teacher_Course();
                $tc->teacher_id = $model->id;
                $tc->course_id  = $model->course_id;
                $tc->save();

                return $this->redirect(['index']);
            }
        }
        return $this->redirect(['index']);
    }

    public function actionUpdate($id)
    {
        if (!$this->isAdmin()) {
            throw new ForbiddenHttpException('Only admins can edit teachers.');
        }

        $model = $this->findModel($id);
        $oldCourseId = $model->course_id;

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            if ($oldCourseId != $model->course_id) {
                \app\models\Teacher_Course::deleteAll([
                    'teacher_id' => $model->id,
                    'course_id'  => $oldCourseId,
                ]);
                $tc = new \app\models\Teacher_Course();
                $tc->teacher_id = $model->id;
                $tc->course_id  = $model->course_id;
                $tc->save();
            }
            return $this->redirect(['index']);
        }
        return $this->redirect(['index']);
    }

    public function actionDelete($id)
    {
        if (!$this->isAdmin()) {
            throw new ForbiddenHttpException('Only admins can delete teachers.');
        }

        \app\models\Teacher_Course::deleteAll(['teacher_id' => $id]);

        $this->findModel($id)->delete();
        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = Teacher::findOne(['id' => $id])) !== null) {
            return $model;
        }
        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}