<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user}}`.
 */
class m250912_213619_create_user_table extends Migration
{
    public function safeUp()
    {
        // Tạo bảng user
        $this->createTable('{{%user}}', [
            'id'                => $this->primaryKey(),
            'username'          => $this->string()->notNull()->unique(),
            'password_hash'     => $this->string()->notNull(),
            'email'             => $this->string()->notNull()->unique(),
            'auth_key'          => $this->string(32),
            'role'              => $this->string(32)->notNull()->defaultValue('user'),
            'status'            => $this->integer()->notNull()->defaultValue('1'),
            'created_at'        => $this->integer()->notNull(),
            'updated_at'        => $this->integer()->notNull(),
        ]);

        // Tạo dữ liệu mẫu admin
        $now = time();
        $this->insert('{{%user}}', [
            'username'          => 'admin',
            'password_hash'     => Yii::$app->security->generatePasswordHash('admin'),
            'email'             => 'admin@gmail.com',
            'auth_key'          => Yii::$app->security->generateRandomString(),
            'role'              => 'admin',
            'status'            => 1,
            'created_at'        => $now,
            'updated_at'        => $now,
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('{{%user}}');
    }
}
