<?php

namespace maximkozhin\user\components;


use maximkozhin\user\models\UserRole;


class User extends \yii\web\User
{
    /** @var array */
    public $loginUrl  = ['site/login'];

    /** @var array  */
    public $logoutUrl = ['site/logout'];

    /**
     * @param string $roleName The Role Alias
     * @return bool
     */
    public function is($roleName)
    {
        /** Checking user is guest */
        if($roleName === 'guest') {
            return $this->isGuest;
        }

        /** Checking user is logged in */
        if($roleName === 'user') {
            return !$this->isGuest;
        }

        /** Checking the 'roleName' string */
        if($roleName) {

            /** @var UserRole[] $userRoles */
            $userRoles = $this->identity->userRoles;

            /** User has roles */
            if($userRoles) {
                foreach($userRoles as $userRole) {
                    if($userRole->alias === $roleName) {
                        return true;
                    }
                }
            }
        }

        return false;
    }

    /**
     * @param string $permissionName
     * @param array $params
     * @param bool $allowCaching
     * @return bool
     */
    public function can($permissionName, $params = [], $allowCaching = true)
    {
        if( (parent::can($permissionName, $params = [], $allowCaching = true)) === true ) {
            return true;
        } else {
            return $this->is($permissionName);
        }
    }
}