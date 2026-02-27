<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Teacher_Course;

/**
 * Teacher_CourseSearch represents the model behind the search form of `app\models\Teacher_Course`.
 */
class Teacher_CourseSearch extends Teacher_Course
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['teacher_id', 'course_id'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     * @param string|null $formName Form name to be used into `->load()` method.
     *
     * @return ActiveDataProvider
     */
    public function search($params, $formName = null)
    {
        $query = Teacher_Course::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params, $formName);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'teacher_id' => $this->teacher_id,
            'course_id' => $this->course_id,
        ]);

        return $dataProvider;
    }
}
