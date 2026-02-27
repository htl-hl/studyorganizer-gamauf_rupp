<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "Assignment".
 *
 * @property int $id
 * @property string $title
 * @property string|null $description
 * @property string $due_date
 * @property int|null $is_done
 * @property int $user_id
 * @property int $course_id
 * @property int $teacher_id
 *
 * @property Course $course
 * @property Teacher $teacher
 * @property User $user
 */
class Assignment extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'Assignment';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['description'], 'default', 'value' => null],
            [['is_done'], 'default', 'value' => 0],
            [['title', 'due_date', 'user_id', 'course_id', 'teacher_id'], 'required'],
            [['description'], 'string'],
            [['due_date'], 'safe'],
            [['is_done', 'user_id', 'course_id', 'teacher_id'], 'integer'],
            [['title'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
            [['course_id'], 'exist', 'skipOnError' => true, 'targetClass' => Course::class, 'targetAttribute' => ['course_id' => 'id']],
            [['teacher_id'], 'exist', 'skipOnError' => true, 'targetClass' => Teacher::class, 'targetAttribute' => ['teacher_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'title' => Yii::t('app', 'Title'),
            'description' => Yii::t('app', 'Description'),
            'due_date' => Yii::t('app', 'Due Date'),
            'is_done' => Yii::t('app', 'Is Done'),
            'user_id' => Yii::t('app', 'User ID'),
            'course_id' => Yii::t('app', 'Course ID'),
            'teacher_id' => Yii::t('app', 'Teacher ID'),
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
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery|UserQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * {@inheritdoc}
     * @return AssignmentQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new AssignmentQuery(get_called_class());
    }

}
