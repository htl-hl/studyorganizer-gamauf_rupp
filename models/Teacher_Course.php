<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "Teacher_Course".
 *
 * @property int $teacher_id
 * @property int $course_id
 *
 * @property Course $course
 * @property Teacher $teacher
 */
class Teacher_Course extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'Teacher_Course';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['teacher_id', 'course_id'], 'required'],
            [['teacher_id', 'course_id'], 'integer'],
            [['teacher_id', 'course_id'], 'unique', 'targetAttribute' => ['teacher_id', 'course_id']],
            [['teacher_id'], 'exist', 'skipOnError' => true, 'targetClass' => Teacher::class, 'targetAttribute' => ['teacher_id' => 'id']],
            [['course_id'], 'exist', 'skipOnError' => true, 'targetClass' => Course::class, 'targetAttribute' => ['course_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'teacher_id' => Yii::t('app', 'Teacher ID'),
            'course_id' => Yii::t('app', 'Course ID'),
        ];
    }

    /**
     * Gets query for [[Course]].
     *
     * @return \yii\db\ActiveQuery|CourseQuery
     */
    public function getCourse()
    {
        return $this->hasOne(Course::class, ['id' => 'course_id']);
    }

    /**
     * Gets query for [[Teacher]].
     *
     * @return \yii\db\ActiveQuery|TeacherQuery
     */
    public function getTeacher()
    {
        return $this->hasOne(Teacher::class, ['id' => 'teacher_id']);
    }

    /**
     * {@inheritdoc}
     * @return Teacher_CourseQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new Teacher_CourseQuery(get_called_class());
    }

}
