<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\behaviors\TimestampBehavior;

class User extends ActiveRecord implements IdentityInterface
{
    public $password; // virtual field để nhận plaintext khi create/update
    
    // Tham chiếu đến bảng user trong db
    public static function tableName() 
    { 
        return '{{%user}}';
    }

    // Tự fill cho created_at và updated_at
    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    public function rules()
    {
        return [
            // Create: tất cả bắt buộc trừ auth_key
            [['username', 'email', 'password', 'role', 'status'], 'required', 'on' => 'create'],
            // Update: tất cả bắt buộc trừ auth_key, password
            [['username', 'email', 'role', 'status'], 'required', 'on' => 'update'],

            // username
            ['username', 'trim'],
            ['username', 'string', 'max' => 255],
            ['username', 'unique', 'message' => 'Username already exists'],

            // password_hash
            ['password', 'string', 'min' => 5, 'max' => 20,
                'tooShort' => 'Password must be at least 5 characters',
                'tooLong' => 'Password cannot exceed 20 characters'],

            // email
            ['email', 'trim'],
            ['email', 'string', 'max' => 255],
            ['email', 'email', 'message' => 'Email format is invalid'],
            ['email', 'unique', 'message' => 'Email already exists'],

            // auth_key
            ['auth_key', 'string', 'max' => 32],

            // role
            ['role', 'in', 'range' => ['admin', 'user'], 
                'message' => 'Role must be either admin or user'],

            // status
            ['status', 'in', 'range' => [0, 1],
                'message' => 'Status must be either 0 or 1'],
            ['status', 'integer'],
        ];
    }

    // Đăng kí scenarios cho action create và update
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['create'] = ['username','email','role','status','password'];
        $scenarios['update'] = ['username','email','role','status','password'];
        return $scenarios;
    }

    // Ẩn các field trước khi trả ra dữ liệu
    public function fields()
    {
        $fields = parent::fields();
        unset($fields['password_hash'], $fields['auth_key']);
        return $fields;
    }

    // Thao tác thực hiện trước khi lưu xuống db
    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) return false;

        if ($this->password !== null && $this->password !== '') {
            $this->password_hash = Yii::$app->security->generatePasswordHash($this->password);
        }

        if ($insert && empty($this->auth_key)) {
            $this->auth_key = Yii::$app->security->generateRandomString();
        }

        return true;
    }

    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    public static function findByUsername($username)
    {
        Yii::debug(['username' => $username], __METHOD__);
        $user = static::findOne(['username' => $username, 'status' => 1]);
        if ($user === null) {
            Yii::warning("User not found or inactive: {$username}", __METHOD__);
        }
        return $user;
    }

    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => 1]);
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
        return $this->auth_key;
    }

    public function validateAuthKey($authKey)
    {
        return $this->auth_key === $authKey;
    }
}
