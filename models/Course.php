<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "Course".
 *
 * @property int $id
 * @property string $course_name
 *
 * @property Assignment[] $assignments
 * @property TeacherCourse[] $teacherCourses
 * @property Teacher[] $teachers
 */
class Course extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'Course';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['course_name'], 'required'],
            [['course_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'course_name' => Yii::t('app', 'Course Name'),
        ];
    }

    /**
     * Gets query for [[Assignments]].
     *
     * @return \yii\db\ActiveQuery|AssignmentQuery
     */
    public function getAssignments()
    {
        return $this->hasMany(Assignment::class, ['course_id' => 'id']);
    }

    /**
     * Gets query for [[TeacherCourses]].
     *
     * @return \yii\db\ActiveQuery|TeacherCourseQuery
     */
    public function getTeacherCourses()
    {
        return $this->hasMany(TeacherCourse::class, ['course_id' => 'id']);
    }

    /**
     * Gets query for [[Teachers]].
     *
     * @return \yii\db\ActiveQuery|TeacherQuery
     */
    public function getTeachers()
    {
        return $this->hasMany(Teacher::class, ['id' => 'teacher_id'])->viaTable('Teacher_Course', ['course_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return CourseQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CourseQuery(get_called_class());
    }

}
