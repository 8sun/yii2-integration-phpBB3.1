<?php

namespace nill\forum;

use Yii;
use yii\web\User;
use yii\web\IdentityInterface;
use yii\base\InvalidParamException;

class PhpBBWebUser extends User {

    /** @var UserIdentity */
    private $_identity;

    public function login(IdentityInterface $identity, $duration = 0) {
        $this->_identity = $identity;
        return parent::login($identity, $duration);
    }

    protected function afterLogin($identity, $fromCookie, $duration) {
        if ($this->_identity !== null) {
            if (\Yii::$app->phpBB->login($this->_identity->username, $this->_identity->password_reg) != 'SUCCESS') {
                throw new InvalidParamException('Не удалось пройти авторизацию на форуме');
            }
        }
        parent::afterLogin($identity, $fromCookie, $duration);
    }

    protected function afterLogout($identity) {
        \Yii::$app->phpBB->logout();
        parent::afterLogout($identity);
    }

}
