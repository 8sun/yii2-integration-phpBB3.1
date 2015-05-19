<?php

namespace nill\forum\models;

use Yii;

/**
 * This is the model class for table "{{%frm_users}}".
 *
 * @property string $user_id
 * @property integer $user_type
 * @property string $group_id
 * @property string $user_permissions
 * @property string $user_perm_from
 * @property string $user_ip
 * @property string $user_regdate
 * @property string $username
 * @property string $username_clean
 * @property string $user_password
 * @property string $user_passchg
 * @property string $user_email
 * @property string $user_email_hash
 * @property string $user_birthday
 * @property string $user_lastvisit
 * @property string $user_lastmark
 * @property string $user_lastpost_time
 * @property string $user_lastpage
 * @property string $user_last_confirm_key
 * @property string $user_last_search
 * @property integer $user_warnings
 * @property string $user_last_warning
 * @property integer $user_login_attempts
 * @property integer $user_inactive_reason
 * @property string $user_inactive_time
 * @property string $user_posts
 * @property string $user_lang
 * @property string $user_timezone
 * @property string $user_dateformat
 * @property string $user_style
 * @property string $user_rank
 * @property string $user_colour
 * @property integer $user_new_privmsg
 * @property integer $user_unread_privmsg
 * @property string $user_last_privmsg
 * @property integer $user_message_rules
 * @property integer $user_full_folder
 * @property string $user_emailtime
 * @property integer $user_topic_show_days
 * @property string $user_topic_sortby_type
 * @property string $user_topic_sortby_dir
 * @property integer $user_post_show_days
 * @property string $user_post_sortby_type
 * @property string $user_post_sortby_dir
 * @property integer $user_notify
 * @property integer $user_notify_pm
 * @property integer $user_notify_type
 * @property integer $user_allow_pm
 * @property integer $user_allow_viewonline
 * @property integer $user_allow_viewemail
 * @property integer $user_allow_massemail
 * @property string $user_options
 * @property string $user_avatar
 * @property string $user_avatar_type
 * @property integer $user_avatar_width
 * @property integer $user_avatar_height
 * @property string $user_sig
 * @property string $user_sig_bbcode_uid
 * @property string $user_sig_bbcode_bitfield
 * @property string $user_jabber
 * @property string $user_actkey
 * @property string $user_newpasswd
 * @property string $user_form_salt
 * @property integer $user_new
 * @property integer $user_reminded
 * @property string $user_reminded_time
 * @property integer $user_in50fpp
 * @property integer $user_done50fpp
 * @property integer $user_mailing
 * @property integer $user_tempkey
 * @property integer $user_check_rating_flag
 */
class phpBBUsers extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%frm_users}}';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('dbforum');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_type', 'group_id', 'user_perm_from', 'user_regdate', 'user_passchg', 'user_email_hash', 'user_lastvisit', 'user_lastmark', 'user_lastpost_time', 'user_last_search', 'user_warnings', 'user_last_warning', 'user_login_attempts', 'user_inactive_reason', 'user_inactive_time', 'user_posts', 'user_style', 'user_rank', 'user_new_privmsg', 'user_unread_privmsg', 'user_last_privmsg', 'user_message_rules', 'user_full_folder', 'user_emailtime', 'user_topic_show_days', 'user_post_show_days', 'user_notify', 'user_notify_pm', 'user_notify_type', 'user_allow_pm', 'user_allow_viewonline', 'user_allow_viewemail', 'user_allow_massemail', 'user_options', 'user_avatar_width', 'user_avatar_height', 'user_new', 'user_reminded', 'user_reminded_time', 'user_in50fpp', 'user_done50fpp', 'user_mailing', 'user_tempkey', 'user_check_rating_flag'], 'integer'],
            [['user_permissions', 'user_sig'], 'required'],
            [['user_permissions', 'user_sig'], 'string'],
            [['user_ip'], 'string', 'max' => 40],
            [['username', 'username_clean', 'user_password', 'user_avatar', 'user_avatar_type', 'user_sig_bbcode_bitfield', 'user_jabber', 'user_newpasswd'], 'string', 'max' => 255],
            [['user_email', 'user_timezone'], 'string', 'max' => 100],
            [['user_birthday', 'user_last_confirm_key'], 'string', 'max' => 10],
            [['user_lastpage'], 'string', 'max' => 200],
            [['user_lang', 'user_dateformat'], 'string', 'max' => 30],
            [['user_colour'], 'string', 'max' => 6],
            [['user_topic_sortby_type', 'user_topic_sortby_dir', 'user_post_sortby_type', 'user_post_sortby_dir'], 'string', 'max' => 1],
            [['user_sig_bbcode_uid'], 'string', 'max' => 8],
            [['user_actkey', 'user_form_salt'], 'string', 'max' => 32],
            [['username_clean'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => Yii::t('ru', 'User ID'),
            'user_type' => Yii::t('ru', 'User Type'),
            'group_id' => Yii::t('ru', 'Group ID'),
            'user_permissions' => Yii::t('ru', 'User Permissions'),
            'user_perm_from' => Yii::t('ru', 'User Perm From'),
            'user_ip' => Yii::t('ru', 'User Ip'),
            'user_regdate' => Yii::t('ru', 'User Regdate'),
            'username' => Yii::t('ru', 'Username'),
            'username_clean' => Yii::t('ru', 'Username Clean'),
            'user_password' => Yii::t('ru', 'User Password'),
            'user_passchg' => Yii::t('ru', 'User Passchg'),
            'user_email' => Yii::t('ru', 'User Email'),
            'user_email_hash' => Yii::t('ru', 'User Email Hash'),
            'user_birthday' => Yii::t('ru', 'User Birthday'),
            'user_lastvisit' => Yii::t('ru', 'User Lastvisit'),
            'user_lastmark' => Yii::t('ru', 'User Lastmark'),
            'user_lastpost_time' => Yii::t('ru', 'User Lastpost Time'),
            'user_lastpage' => Yii::t('ru', 'User Lastpage'),
            'user_last_confirm_key' => Yii::t('ru', 'User Last Confirm Key'),
            'user_last_search' => Yii::t('ru', 'User Last Search'),
            'user_warnings' => Yii::t('ru', 'User Warnings'),
            'user_last_warning' => Yii::t('ru', 'User Last Warning'),
            'user_login_attempts' => Yii::t('ru', 'User Login Attempts'),
            'user_inactive_reason' => Yii::t('ru', 'User Inactive Reason'),
            'user_inactive_time' => Yii::t('ru', 'User Inactive Time'),
            'user_posts' => Yii::t('ru', 'User Posts'),
            'user_lang' => Yii::t('ru', 'User Lang'),
            'user_timezone' => Yii::t('ru', 'User Timezone'),
            'user_dateformat' => Yii::t('ru', 'User Dateformat'),
            'user_style' => Yii::t('ru', 'User Style'),
            'user_rank' => Yii::t('ru', 'User Rank'),
            'user_colour' => Yii::t('ru', 'User Colour'),
            'user_new_privmsg' => Yii::t('ru', 'User New Privmsg'),
            'user_unread_privmsg' => Yii::t('ru', 'User Unread Privmsg'),
            'user_last_privmsg' => Yii::t('ru', 'User Last Privmsg'),
            'user_message_rules' => Yii::t('ru', 'User Message Rules'),
            'user_full_folder' => Yii::t('ru', 'User Full Folder'),
            'user_emailtime' => Yii::t('ru', 'User Emailtime'),
            'user_topic_show_days' => Yii::t('ru', 'User Topic Show Days'),
            'user_topic_sortby_type' => Yii::t('ru', 'User Topic Sortby Type'),
            'user_topic_sortby_dir' => Yii::t('ru', 'User Topic Sortby Dir'),
            'user_post_show_days' => Yii::t('ru', 'User Post Show Days'),
            'user_post_sortby_type' => Yii::t('ru', 'User Post Sortby Type'),
            'user_post_sortby_dir' => Yii::t('ru', 'User Post Sortby Dir'),
            'user_notify' => Yii::t('ru', 'User Notify'),
            'user_notify_pm' => Yii::t('ru', 'User Notify Pm'),
            'user_notify_type' => Yii::t('ru', 'User Notify Type'),
            'user_allow_pm' => Yii::t('ru', 'User Allow Pm'),
            'user_allow_viewonline' => Yii::t('ru', 'User Allow Viewonline'),
            'user_allow_viewemail' => Yii::t('ru', 'User Allow Viewemail'),
            'user_allow_massemail' => Yii::t('ru', 'User Allow Massemail'),
            'user_options' => Yii::t('ru', 'User Options'),
            'user_avatar' => Yii::t('ru', 'User Avatar'),
            'user_avatar_type' => Yii::t('ru', 'User Avatar Type'),
            'user_avatar_width' => Yii::t('ru', 'User Avatar Width'),
            'user_avatar_height' => Yii::t('ru', 'User Avatar Height'),
            'user_sig' => Yii::t('ru', 'User Sig'),
            'user_sig_bbcode_uid' => Yii::t('ru', 'User Sig Bbcode Uid'),
            'user_sig_bbcode_bitfield' => Yii::t('ru', 'User Sig Bbcode Bitfield'),
            'user_jabber' => Yii::t('ru', 'User Jabber'),
            'user_actkey' => Yii::t('ru', 'User Actkey'),
            'user_newpasswd' => Yii::t('ru', 'User Newpasswd'),
            'user_form_salt' => Yii::t('ru', 'User Form Salt'),
            'user_new' => Yii::t('ru', 'User New'),
            'user_reminded' => Yii::t('ru', 'User Reminded'),
            'user_reminded_time' => Yii::t('ru', 'User Reminded Time'),
            'user_in50fpp' => Yii::t('ru', 'Юзер участвует в программе 50 FPP'),
            'user_done50fpp' => Yii::t('ru', 'Юзер получил выплату по программе 50FPP'),
            'user_mailing' => Yii::t('ru', 'Подписан ли пользователь на рассылки?'),
            'user_tempkey' => Yii::t('ru', 'Случайный ключ пользователя'),
            'user_check_rating_flag' => Yii::t('ru', 'флаг нужно ли проверять пользователя'),
        ];
    }
}
