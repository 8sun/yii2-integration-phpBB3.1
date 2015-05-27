yii2-integration-phpBB3.1
=========================

This extension is intended to integrate the phpBB 3.1.x with the Yii2. 
It allows automatic registration of users from the site to the forum, as well as authorization, change email, password.
An extension [yii2-users-module](https://github.com/vova07/yii2-users-module) was used as a users model, but you can connect and configure the behavior of your model.

About
-----
**Version:** 0.3.7

**Authors:** yiiframework.ru, Felix Manea, ƒмитрий ≈лисеев, Mefistophell Nill

Used
----
- Yii framework 2.x
- Yii-Start Applications - yii2-start-users (https://github.com/vova07/yii2-users-module)
- phpBB 3.1.x

Integration
============

Part 1: Install via Composer
--------------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require nill/forum "dev-master"
```

or add

```
"nill/forum": "dev-master"
```

to the require section of your `composer.json` file.

Install from an Archive File
----------------------------

- Add folder `/vendor/nill/forum/`

[Github: yii2-integration-phpBB3.1](https://github.com/8sun/yii2-integration-phpBB3.1)

- Download and unpack files to created directory

- Add to extensions `/vendor/yiisoft/extensions.php`:

```
'nill/forum' => 
    array (
        'name' => 'nill/forum',
        'version' => '0.1.0.0',
        'alias' => 
        array (
            '@nill/forum' => $vendorDir . '/nill/forum',
        ),
    ),
```

Part 2: Add component and set config
----------------------------------------

- Add component to config `/common/config/main.php`

```
        'phpBB' => [
            'class' => 'nill\forum\phpBB',
            'path' => dirname(dirname(__DIR__)). '\forum',
        ],
```

- Add `request` and change `user` to components configurations:

```
        'user' => [
            'class' => 'nill\forum\PhpBBWebUser',
            'loginUrl'=>['/login'],
            'identityClass' => 'vova07\users\models\frontend\User',
            // enable cookie-based authentication
            // 'allowAutoLogin' => true,
        ],
        'request' => [
            'baseUrl' => $_SERVER['DOCUMENT_ROOT'] . $_SERVER['PHP_SELF'] != $_SERVER['SCRIPT_FILENAME'] ? 'http://' . $_SERVER['HTTP_HOST'] : '',
        ],
```

Part 3: Change forum settings
--------------------------

- Change method `get_container_filename()` in `\forum\phpbb\di\container_builder.php`

```
    protected function get_container_filename() {

        // Change the line to synchronize with the site
        // $filename = str_replace(array('/', '.'), array('slash', 'dot'), $this->phpbb_root_path);

        $filename = str_replace(array('\\', '/', '.'), array('slash', 'slash', 'dot'), $this->phpbb_root_path);
        return $this->phpbb_root_path . 'cache/container_' . $filename . '.' . $this->php_ext;
    }
```

- Change `frm_config` field `cookie_domain` on your domain: 
**example** - `domain.loc`

>CAUTION: This setting is also stored in a file, clear the cache if you are not working.

Part 4: Add behavior to user Model
----------------------------------

####Change User Model

- Add this code to the top User class:

`use nill\forum\behaviors\PhpBBUserBahavior;`

and

```
    /**
     * The variables required for integration with the forum
     * @var string $password_reg - old password
     * @var string $password_new - new password
     */
    public $password_reg;
    public $password_new;
```

- Add or change this code before method `getId`

```
    /**
     * Behavior PhpBBUserBahavior necessary for integration with the forum
     */
    public function behaviors() {
        return [
            'timestampBehavior' => [
                'class' => TimestampBehavior::className(),
            ],
            'PhpBBUserBahavior' => [
                'class' => PhpBBUserBahavior::className(),
                'userAttr' => 'username',
                'newpassAttr' => 'password_new',
                'passAttr' => 'password',
                'emailAttr' => 'email',
            ],
        ];
    }
```

- Change next methods: 

```
    public function validatePassword($password) {
        $this->password_reg = $password;

        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }
```

and

```
    public function password($password) {
        $this->password_new = $password;

        $this->setPassword($password);
        return $this->save(false);
    }
```

- Comment or delete in `\vendor\nill\users\models\frontend\PasswordForm.php`
- Comment or delete in `\vendor\nill\users\models\frontend\Email.php`

this string

```
use vova07\users\models\User;
```


FORUM in Template yii2
============

**1. Create in folder `\forum` file `yiiapp.php`**

```
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

require(__DIR__ . '/../vendor/autoload.php');
require(__DIR__ . '/../vendor/yiisoft/yii2/Yii.php');
require(__DIR__ . '/../common/config/aliases.php');

$config = yii\helpers\ArrayHelper::merge(
    require(__DIR__ . '/../common/config/main.php'),
    require(__DIR__ . '/../common/config/main-local.php'),
    require(__DIR__ . '/../frontend/config/main.php'),
    require(__DIR__ . '/../frontend/config/main-local.php')
);

if ('YII_ENV_DEV') {
    $config['components']['assetManager']['basePath'] = '@app/web/assets';
}

\Yii::setAlias('@app', '/../');
$application = new yii\web\Application($config);

```

**2. Create in folder `\frontend\views\layouts\` file `forum.php`. This template forum.**

- This template forum. 

```
<?= $content ?>
```

**3. Add in top file `\forum\index.php` next code:**

```
//************************   FORUM Yii **********************************

include "yiiapp.php";

$controller = new yii\web\Controller('7','forum');
 \Yii::$app->controller = $controller;
ob_start();

//************************  *********  **********************************
```

>This code need used on all pages forum, exemple: viewforum.php, viewtopic.php...

**4. Add in file `\forum\includes\functions.php` next code to end page_footer() function: (row:5310)**

```
garbage_collection();
        
//************************   FORUM Yii **********************************
        if (class_exists('Yii', false) && \Yii::$app->controller !== null) {
                $content = ob_get_clean();
                echo \Yii::$app->controller->render('//layouts/forum', ['content' => $content]);
        }
//************************   ********** **********************************
        
	if ($exit_handler)
	{
		exit_handler();
	}
```

>If not working then: 

In file `forum\phpbb\request\request.php` change next:

`protected $super_globals_disabled = false;` on `protected $super_globals_disabled = true;` (row:44)


Synchronization
===============

To synchronize need to do the relation between the users of the site and forum.

Add to User model next row:
---------------------------

```
use nill\forum\models\phpBBUsers;

public function getPhpbbuser()
{
    return $this->hasOne(phpBBUsers::className(), ['username' => 'username']);
}
```

Exemple view:

```
<?php $models = User::findOne(Yii::$app->user->id); ?>
Your Messages <a href="/forum/ucp.php?i=pm&folder=inbox">New: <?php if($models) echo $models->phpbbuser->user_unread_privmsg; ?></a>
```