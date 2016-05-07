yii2-integration-phpBB3.1
=========================

This extension is intended to integrate the phpBB 3.1.x with the Yii2. 
Extension is intended for synchronization of a login, registration and editing of profile data, such as an e-mail address and  password. 
An extension [yii2-users-module](https://github.com/vova07/yii2-users-module) was used as a users model, but you can connect and configure the behavior of your model.

About
-----
**Version:** 0.3.7

**Authors:** yiiframework.ru, Felix Manea, Äìèòðèé Åëèñååâ, Mefistophell Nill

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

- Download and unpack files into created directory

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

Part 2: Add a component and set configurations
----------------------------------------

- Add component to config `/common/config/main.php`

```
        'phpBB' => [
            'class' => 'nill\forum\phpBB',
            'path' => dirname(dirname(__DIR__)). '\forum',
        ],
```

- Add the `request` and change the `user` in configuration of components:

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

Part 3: Change a forum settings
--------------------------

- Change the method `get_container_filename()` into `\forum\phpbb\di\container_builder.php`

```
    protected function get_container_filename() {

        // Change the line to synchronize with the site
        // $filename = str_replace(array('/', '.'), array('slash', 'dot'), $this->phpbb_root_path);

        $filename = str_replace(array('\\', '/', '.'), array('slash', 'slash', 'dot'), $this->phpbb_root_path);
        return $this->phpbb_root_path . 'cache/container_' . $filename . '.' . $this->php_ext;
    }
```

- Find and change in the table `frm_config` database field `cookie_domain` to your domain: 
**example** - `domain.loc`

>CAUTION: This option also is in a cache file, clear your cache if will not work.

Part 4: Add behavior to user Model
----------------------------------

####Change User Model

- Add this code to the top of User class:

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

- Add or change this code before the method `getId`

```
    /**
     * Behavior PhpBBUserBahavior necessary for integration with the forum
     */
    public function behaviors() {
        return [
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

>If you use yii2-start-users then do following instructions

- Change the following methods:

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

- Comment or delete in `\vendor\vova07\users\models\frontend\PasswordForm.php`
- Comment or delete in `\vendor\vova07\users\models\frontend\Email.php`

this string

```
use vova07\users\models\User;
```


FORUM in Template yii2
============

**1. Create into the folder `\forum` the file `yiiapp.php`**

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

**2. Create into the folder `\frontend\views\layouts\` the file `forum.php`. This template forum.**

```
<?= $content ?>
```

**3. Add to top of the file `\forum\index.php` the following code:

```
//************************   FORUM Yii **********************************

include "yiiapp.php";

$controller = new yii\web\Controller('7','forum');
 \Yii::$app->controller = $controller;
ob_start();

//************************  *********  **********************************
```

>This code should be used to the all general pages of the forum, such as: viewforum.php, viewtopic.php...

**4. Add to the file `\forum\includes\functions.php` the following code into the end of page_footer() function: (row:5310)**

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

>If will not work then: 

In the file `forum\phpbb\request\request.php`chang the following string:

`protected $super_globals_disabled = false;` on `protected $super_globals_disabled = true;` (row:44)


Synchronization
===============

Synchronization is necessary for a relation between the users of the site and the forum.

Add to User model the following row:
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
