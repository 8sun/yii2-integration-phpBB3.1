yii2-integration-phpBB3.1
=========================

About
-----
**Version:** 0.3.7

**Authors:** yiiframework.ru, Felix Manea, ƒмитрий ≈лисеев, Mefistophell Nill

Used
----
- Yii framework 2.x
- Yii-Start Applications - yii2-start-users
- phpBB 3.1.x

Integration
============

Part 1: Download and set phpBB-component-class 
--------------------------

- Add folder `/vendor/nill/forum/`

[Github: yii2-integration-phpBB3.1](https://github.com/8sun/yii2-integration-phpBB3.1)

- Download and unpack files to created directory

Part 2: Add component and set config
----------------------------------------

- Add component to config `/common/config/main.php`

```
        'phpBB' => [
            'class' => 'nill\forum\phpBB',
            'path' => dirname(dirname(__DIR__)). '\forum',
        ],
```

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
