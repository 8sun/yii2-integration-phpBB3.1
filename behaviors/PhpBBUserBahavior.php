<?php

namespace nill\users\behaviors;

use yii\db\ActiveRecord;

/**
 * Поведение расширяющее модель User
 * Используется для изменений данных от пользователя,
 * а так же для сквозной регистрации в phpBB 3.1.x
 */
class PhpBBUserBahavior extends \yii\base\Behavior {

    public $userAttr = 'username';
    public $newpassAttr = 'password_new';
    public $passAttr = 'password';
    public $emailAttr = 'email';

    /**
     * EVENT_BEFORE_INSERT и EVENT_BEFORE_UPDATE
     * возможно лучше заменить на EVENT_AFTER_VALIDATE
     */
    public function events() {
        return [
            ActiveRecord::EVENT_AFTER_UPDATE => 'afterUpdate',
            ActiveRecord::EVENT_AFTER_INSERT => 'afterInsert',
            ActiveRecord::EVENT_BEFORE_UPDATE => 'confirm',
        ];
    }

    /**
     * Это метот регистрации новых пользователей
     */
    public function afterInsert($event) {
        \Yii::$app->phpBB->userAdd($this->owner->{$this->userAttr}, $this->owner->{$this->passAttr}, $this->owner->{$this->emailAttr}, 2);
    }

    /**
     * Это метод для смены пароля
     */
    public function afterUpdate($event) {
        if ($this->owner->{$this->newpassAttr}) {
            \Yii::$app->phpBB->changePassword($this->owner->{$this->userAttr}, $this->owner->{$this->newpassAttr});
        }
    }

    /**
     * Это метод подтверждения e-mail
     * В случае успешной проверки e-mail изменяется и на форуме
     */
    public function confirm() {
        if ($this->owner->{$this->emailAttr}) {
            \Yii::$app->phpBB->changeEmail($this->owner->{$this->userAttr}, $this->owner->{$this->emailAttr});
        }
    }

    /**
     * Пользователи не могут удалять свои аккауны с сайта и форума
     * Но это можно сделать из админ-панели
     * Следует перенести этот метод в backend
     */
    public function afterDelete($event) {
        Yii::$app->phpBB->userDelete($this->owner->{$this->userAttr});
    }

}
