<?php

namespace maximkozhin\user\models;

use Yii;

/**
 * This is the model class for table "{{%user_role}}".
 *
 * @property integer    $user_id
 * @property string     $alias
 *
 * @property Role       $role
 * @property User       $user
 *
 * @author Maxim Kozhin
 */
class UserRole extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_role}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'alias'], 'required'],
            [['user_id'], 'integer'],
            [['alias'], 'string', 'max' => 32],
            [['alias'], 'exist', 'skipOnError' => true, 'targetClass' => Role::className(), 'targetAttribute' => ['alias' => 'alias']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'User ID',
            'alias' => 'Alias',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRole()
    {
        return $this->hasOne(Role::className(), ['alias' => 'alias']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
