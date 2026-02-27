<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[Teacher_Course]].
 *
 * @see Teacher_Course
 */
class Teacher_CourseQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return Teacher_Course[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Teacher_Course|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
