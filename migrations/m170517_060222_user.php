<?php

use yii\db\Migration;

class m170517_060222_user extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%user}}',[
            'id'             => $this->primaryKey(10)->unsigned(),
            'username'       => $this->string(32)->notNull()->unique(),
            'password'       => $this->string(255),
            'email'          => $this->string(64)->unique(),
            'password_token' => $this->string(255),
            'email_token'    => $this->string(255),
            'access_token'   => $this->string(255)->unique(),
            'auth_key'       => $this->string(255)->notNull()->unique(),
            'status'         => $this->boolean(),
            'created_at'     => $this->integer(10)->unsigned(),
            'updated_at'     => $this->integer(10)->unsigned(),
        ]);
        $this->createIndex('index-user-status', '{{%user}}', 'status');


        $this->createTable('{{%role}}',[
            'alias'     => $this->string(32)->notNull(),
            'name'      => $this->string(32)->notNull(),
        ]);
        $this->addPrimaryKey('pk-role', '{{%role}}', 'alias');

        $this->createTable('{{%user_role}}',[
            'user_id'   => $this->integer(10)->notNull()->unsigned(),
            'alias'     => $this->string(32)->notNull()->unsigned(),
        ],$tableOptions);
        $this->addPrimaryKey('pk-user_role', '{{%user_role}}', ['user_id','alias']);
        $this->addForeignKey('fk-user_role-user', '{{%user_role}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-user_role-role', '{{%user_role}}', 'alias', '{{%role}}', 'alias', 'CASCADE', 'CASCADE');

    }

    public function down()
    {
        $this->dropForeignKey('fk-user_role-role', '{{%user_role}}');
        $this->dropForeignKey('fk-user_role-user', '{{%user_role}}');

        $this->dropTable('{{%user_role}}');
        $this->dropTable('{{%role}}');
        $this->dropTable('{{%user}}');
    }
}
