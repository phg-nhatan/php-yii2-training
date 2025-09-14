<?php

namespace app\models\forms;

use Yii;
use app\models\User;
use yii\base\Model;

class LoginForm extends Model
{
    public const LOGIN_DURATION = 120;

    public $username;
    public $password;

    private ?User $_user = null;

    public function rules()
    {
        return [
            [['username', 'password'], 'required'],
            ['password', 'validatePassword'],
        ];
    }

    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();

            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Incorrect username or password.');
            }
        }
    }

    public function login()
    {
        if (!$this->validate()) {
            return false;
        }

        $user = $this->getUser();

        return $user
            ? Yii::$app->user->login($user, self::LOGIN_DURATION)
            : false;
    }

    public function getUser()
    {
        if ($this->_user === null) {
            $this->_user = User::findByUsername($this->username);
        }
        return $this->_user;
    }
}
