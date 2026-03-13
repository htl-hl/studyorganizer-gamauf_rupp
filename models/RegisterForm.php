<?php

namespace app\models;

use Yii;
use yii\base\Model;

class RegisterForm extends Model
{
    public $username;
    public $password;
    public $confirmPassword;

    public function rules()
    {
        return [
            [['username', 'password', 'confirmPassword'], 'required'],
            [['username', 'password', 'confirmPassword'], 'string', 'max' => 255],
            ['confirmPassword', 'compare', 'compareAttribute' => 'password', 'message' => 'Passwords do not match.'],
            ['username', 'unique', 'targetClass' => User::class, 'message' => 'This username is already taken.'],
        ];
    }

    public function register()
    {
        if (!$this->validate()) {
            return false;
        }

        $user = new User();
        $user->username  = $this->username;
        $user->user_role = 'user';
        $user->setPassword($this->password);

        return $user->save();
    }
}