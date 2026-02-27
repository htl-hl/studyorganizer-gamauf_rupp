<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "Teacher".
 *
 * @property int $id
 * @property string $teacher_name
 * @property int $course_id
 * @property int|null $is_active
 *
 * @property Assignment[] $assignments
 * @property Course[] $courses
 * @property TeacherCourse[] $teacherCourses
 */
class Teacher extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'Teacher';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['is_active'], 'default', 'value' => 1],
            [['teacher_name', 'course_id'], 'required'],
            [['course_id', 'is_active'], 'integer'],
            [['teacher_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'teacher_name' => Yii::t('app', 'Teacher Name'),
            'course_id' => Yii::t('app', 'Course ID'),
            'is_active' => Yii::t('app', 'Is Active'),
        ];
    }

    /**
     * Gets query for [[Assignments]].
     *
     * @return \yii\db\ActiveQuery|AssignmentQuery
     */
    public function getAssignments()
    {
        return $this->hasMany(Assignment::class, ['teacher_id' => 'id']);
    }

    /**
     * Gets query for [[Courses]].
     *
     * @return \yii\db\ActiveQuery|CourseQuery
     */
    public function getCourses()
    {
        return $this->hasMany(Course::class, ['id' => 'course_id'])->viaTable('Teacher_Course', ['teacher_id' => 'id']);
    }

    /**
     * Gets query for [[TeacherCourses]].
     *
     * @return \yii\db\ActiveQuery|TeacherCourseQuery
     */
    public function getTeacherCourses()
    {
        return $this->hasMany(TeacherCourse::class, ['teacher_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return TeacherQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TeacherQuery(get_called_class());
    }

}
