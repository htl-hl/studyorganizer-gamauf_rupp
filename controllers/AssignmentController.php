<?php
namespace app\controllers;

use Yii;
use app\models\Assignment;
use app\models\AssignmentSearch;
use app\models\Teacher;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AssignmentController implements the CRUD actions for Assignment model.
 */
class AssignmentController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Assignment models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new AssignmentSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);
        $courses = \app\models\Course::find()->all(); // oder dein Subject-Model

        return $this->render('index', [
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
            'courses'      => $courses,
        ]);
    }

    /**
     * Displays a single Assignment model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Assignment model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Assignment();
        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $model->user_id = Yii::$app->user->id;
                if ($model->save()) {
                    return $this->redirect(['index']);
                } else {
                    // Zeigt Validierungsfehler
                    var_dump($model->errors);
                    die();
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

    /**
     * Updates an existing Assignment model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->redirect(['index']);
    }

    /**
     * Deletes an existing Assignment model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Assignment model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Assignment the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Assignment::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
