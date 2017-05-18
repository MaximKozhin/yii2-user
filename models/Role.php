<?php

namespace maximkozhin\user\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%role}}".
 *
 * @property string     $alias
 * @property string     $name
 *
 * @property UserRole[] $userRoles
 * @property User[]     $users
 *
 * @author Maxim Kozhin
 */
class Role extends ActiveRecord
{
    /**
     * Creating a new Role
     *
     * @param string $alias
     * @param string|null $name
     * @return bool
     */
    public static function add($alias, $name = null)
    {
        if($role = self::findOne($alias)) {
            $role->name = $name ?? $alias;
            return $role->save();
        } else {
            $role = new Role(['alias'=>$alias, 'name' => $name ?? $alias]);
            return $role->save();
        }
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%role}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['alias', 'name'], 'required'],
            [['alias', 'name'], 'string', 'max' => 32],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'alias' => 'Alias',
            'name' => 'Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserRoles()
    {
        return $this->hasMany(UserRole::className(), ['alias' => 'alias']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['id' => 'user_id'])
            ->viaTable('{{%user_role}}', ['alias' => 'alias']);
    }
}
