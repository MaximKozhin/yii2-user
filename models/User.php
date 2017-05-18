<?php

namespace maximkozhin\user\models;

use Yii;
use yii\base\InvalidParamException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "{{%user}}".
 *
 * @property integer            $id
 * @property string             $username
 * @property string             $password
 * @property string             $email
 * @property string             $password_token
 * @property string             $email_token
 * @property string             $access_token
 * @property string             $auth_key
 * @property integer            $status
 * @property integer            $created_at
 * @property integer            $updated_at
 *
 * @property array              $statuses
 * @property string             $statusToString
 *
 * @property UserRole[]         $userRoles
 * @property Role[]             $roles
 *
 * @author Maxim Kozhin
 */
class User extends ActiveRecord implements IdentityInterface
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * @param $alias
     * @return bool
     */
    public function addRole($alias)
    {
        if($role = Role::findOne($alias)) {
            if( ($userRole = UserRole::findOne(['user_id'=>$this->id, 'alias' => $role->alias])) === null ) {
                $userRole = new UserRole(['user_id'=>$this->id, 'alias' => $role->alias]);
            }
            return $userRole->save();
        } else {
            throw new InvalidParamException("Role with alias '{$alias}' not found");
        }
    }

    /**
     * @param $alias
     * @return bool|false|int
     */
    public function deleteRole($alias)
    {
        if($role = Role::findOne($alias)) {
            if( ($userRole = UserRole::findOne(['user_id'=>$this->id, 'alias' => $role->alias]))) {
                return $userRole->delete();
            }
            return true;
        }
        throw new InvalidParamException("Role with alias '{$alias}' not found");
    }

    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        if($this->isNewRecord) {
            $this->created_at = $this->updated_at = time();
        } else {
            $this->updated_at = time();
        }
        return parent::beforeSave($insert);
    }

    const STATUS_BLOCKED = 0;
    const STATUS_WAIT    = 1;
    const STATUS_ACTIVE  = 2;

    /**
     * @return array
     */
    public static function getStatuses()
    {
        return [
            self::STATUS_ACTIVE     => 'Активен',
            self::STATUS_WAIT       => 'Не подтвержден',
            self::STATUS_BLOCKED    => 'Заблокирован',
        ];
    }

    /**
     * @return string|null
     */
    public function getStatusToString()
    {
        return $this->statuses[$this->status];
    }

    /**
     * @param int|string $id
     * @return array|null|ActiveRecord
     */
    public static function findIdentity($id)
    {
        return self::find()->with('roles')
            ->where(['id'=>$id, 'status'=>self::STATUS_ACTIVE])
            ->limit(1)->one();
    }

    /**
     * @param mixed $token
     * @param null  $type
     * @return \maximkozhin\user\models\User | null
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return null;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @param string $authKey
     * @return bool
     */
    public function validateAuthKey($authKey)
    {
        return $this->auth_key === $authKey;
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'auth_key'], 'required'],
            [['status', 'created_at', 'updated_at'], 'integer'],
            [['username'], 'string', 'max' => 32],
            [['password', 'password_token', 'email_token', 'auth_key', 'access_token'], 'string', 'max' => 255],
            [['email'], 'string', 'max' => 64],
            [['username'], 'unique'],
            [['auth_key'], 'unique'],
            [['access_token'], 'unique'],
            [['email'], 'unique'],
            [['email'], 'email'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'            => 'ID',
            'username'      => 'Username',
            'password'      => 'Password',
            'email'         => 'Email',
            'password_token'=> 'Password Token',
            'email_token'   => 'Email Token',
            'auth_key'      => 'Auth Key',
            'status'        => 'Status',
            'created_at'    => 'Created At',
            'updated_at'    => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserRoles()
    {
        return $this->hasMany(UserRole::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRoles()
    {
        return $this->hasMany(Role::className(), ['alias' => 'alias'])
            ->viaTable('{{%user_role}}', ['user_id' => 'id']);
    }
}
