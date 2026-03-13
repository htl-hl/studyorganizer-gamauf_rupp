<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * User model
 *
 * @property int $id
 * @property string $username
 * @property string $password_hash
 * @property string $user_role
 */
class User extends ActiveRecord implements IdentityInterface
{
    public static function tableName()
    {
        return 'User';
    }

    public function rules()
    {
        return [
            [['username', 'password_hash', 'user_role'], 'required'],
            [['username'], 'string', 'max' => 255],
            [['password_hash'], 'string', 'max' => 255],
            [['user_role'], 'string', 'max' => 255],
            [['username'], 'unique'],
        ];
    }

    // ── IdentityInterface ────────────────────────────────────

    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id]);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return null;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAuthKey()
    {
        return null;
    }

    public function validateAuthKey($authKey)
    {
        return false;
    }

    // ── Helpers ──────────────────────────────────────────────

    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username]);
    }

    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }
}