<?php

namespace app\controllers;

use app\models\Teacher;
use app\models\TeacherSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TeacherController implements the CRUD actions for Teacher model.
 */
class TeacherController extends Controller
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
     * Lists all Teacher models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new TeacherSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);
        $courses = \app\models\Course::find()->all();

        return $this->render('index', [
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
            'courses'      => $courses,
        ]);
    }

    /**
     * Displays a single Teacher model.
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
     * Creates a new Teacher model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    // actionCreate – redirect zu index statt view
    public function actionCreate()
    {
        $model = new Teacher();
        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                // Eintrag in Teacher_Course anlegen
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
        $model = $this->findModel($id);
        $oldCourseId = $model->course_id;

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            // Teacher_Course aktualisieren falls Course geändert wurde
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

    /**
     * Deletes an existing Teacher model.
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
     * Finds the Teacher model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Teacher the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Teacher::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
