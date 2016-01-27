<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use frontend\models\User;



class ProfileForm extends Model
{
    public $username;
    public $email;
    public $subject;
    public $mobile;
    public $avatar;
    public $verifyCode;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // name, email, subject and body are required
            [['username', 'email'], 'required'],
            // email has to be a valid email address
            
            [['avatar'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, gif'],
            // verifyCode needs to be entered correctly
            ['verifyCode', 'captcha'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'verifyCode' => 'Verification Code',
        ];
    }

    /**
     * Sends an email to the specified email address using the information collected by this model.
     *
     * @param  string  $email the target email address
     * @return boolean whether the email was sent
     */
    public function sendEmail($email)
    {
        return Yii::$app->mailer->compose()
            ->setTo($email)
            ->setFrom([$this->email => $this->name])
            ->setSubject($this->subject)
            ->setTextBody($this->body)
            ->send();
    }



}
