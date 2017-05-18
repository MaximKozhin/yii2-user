User Extension for Yii2 
========================
User Extension for Yii2 

Via composer
------------

add to your 'require' section in file 'composer.json'
```
 "require": {
       ...
        "maximkozhin/yii2-user": "*"
    },
```
or run command

```
$ composer require maximkozhin/yii2-user
```


**1. Migration | Миграция**
---------------------------

run migration:
```
yii migrate/up --migrationPath=@vendor/maximkozhin/yii2-user/migrations
```

to revert migration run:
```
yii migrate/down --migrationPath=@vendor/maximkozhin/yii2-user/migrations
```

**2. Configuration | Конфигурация**
-----------------------------------
Copy files into 'your-repo' directory.


after add into components
```
...
'componetns' => [
    ...
    'user' => [
            'class' => 'maximkozhin\user\components\User',
            'identityClass' => 'maximkozhin\user\models\User',
            'loginUrl'  => 'url/alias/to/login'
            'logoutUrl' => 'url/alias/to/logout'
        ],
    ...
]
...
```

**3. Usage | Использование**
----------------------------

Into your code you may use 

User is guest

```
Yii::$app->user->is('guest');
```

User is logged in

```
Yii::$app->user->is('user');
```


**3. Usage Role | Использование ролей**
----------------------------

To create another role typo an alias of role and name of role.
```
$alias = 'admin';
\maximkozhin\user\models\Role::add($alias, 'Администратор');
```

Add this role for user
```
/** @var \maximkozhin\user\models\User $user*/
$user->addRole($alias);
```

after that you may check user role
```
Yii::$app->user->is('admin');
or
$user->is('admin');
```

to delete role for user
```
/** @var \maximkozhin\user\models\User $user*/
$user->deleteRole($alias);
```

**4. Module | Модуль пользователей**
------------------------------------

```
...
'modules' => [
    ...
    'user-module-name' => [
        'class' => 'maximkozhin\user\modules\user\Module',
    ],
    ...
],
...
```




