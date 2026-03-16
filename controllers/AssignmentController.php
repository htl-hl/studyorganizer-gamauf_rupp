<?php
namespace app\controllers;

use Yii;
use app\models\Assignment;
use app\models\AssignmentSearch;
use app\models\Teacher;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

class AssignmentController extends Controller
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

    public function actionIndex()
    {
        $searchModel = new AssignmentSearch();

        $params = $this->request->queryParams;
        $dataProvider = $searchModel->search($params);
        $dataProvider->query->andWhere(['user_id' => Yii::$app->user->id]);

        $courses = \app\models\Course::find()->all();

        return $this->render('index', [
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
            'courses'      => $courses,
        ]);
    }

    public function actionView($id)
    {
        $model = $this->findModel($id);
        if ($model->user_id !== Yii::$app->user->id) {
            throw new ForbiddenHttpException('Access denied.');
        }
        return $this->render('view', ['model' => $model]);
    }

    public function actionCreate()
    {
        $model = new Assignment();
        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $model->user_id = Yii::$app->user->id;
                if ($model->save()) {
                    return $this->redirect(['index']);
                }
            }
        }
        return $this->redirect(['index']);
    }

    public function actionTeachersByCourse($course_id)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $teachers = Teacher::find()
            ->join('INNER JOIN', 'Teacher_Course', 'Teacher_Course.teacher_id = Teacher.id')
            ->where(['Teacher_Course.course_id' => (int)$course_id])
            ->all();

        return array_map(fn($t) => [
            'id'   => $t->id,
            'name' => $t->teacher_name,
        ], $teachers);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->user_id !== Yii::$app->user->id) {
            throw new ForbiddenHttpException('Access denied.');
        }

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->redirect(['index']);
    }

    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        if ($model->user_id !== Yii::$app->user->id) {
            throw new ForbiddenHttpException('Access denied.');
        }

        $model->delete();
        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = Assignment::findOne(['id' => $id])) !== null) {
            return $model;
        }
        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}