<?php

namespace app\models;

use Yii;
use yii\base\Model;
use common\models\User;


class ProfileUpdateForm extends User
{
    public $firstname;
    public $lastname;
    public $birthday;
    public $sex;
    public $country;
    public $city_id;
    public $phone;
    public $relationship;
    public $job;
    public $activity;
    public $interests;
    public $study;

    private $_user;

    public function __construct($id, $config = [])
    {
        $this->_user = User::findIdentity($id);
        $user = $this->_user;
        $this->firstname = $user->firstname;
        $this->lastname = $user->lastname;
        $this->birthday = date('d.m.Y', $user->birthday);
        $this->sex = $user->sex;
        $this->country = $user->country;
        $this->city_id = $user->city_id;
        $this->phone = $user->phone;
        $this->relationship = $user->relationship;
        $this->job = $user->job;
        $this->activity = $user->activity;
        $this->interests = $user->interests;
        $this->study = $user->study;

        if (!$this->_user) {
            throw new InvalidArgumentException('Wrong user.');
        }
        parent::__construct($config);
    }

    public function updateUser()
    {
        $user = $this->_user;
        $user->firstname = $this->firstname;
        $user->lastname = $this->lastname;
        $user->birthday = strtotime($this->birthday);
        $user->sex = $this->sex;
        $user->country = $this->country;
        $user->city_id = $this->city_id;
        $user->phone = $this->phone;
        $user->relationship = $this->relationship;
        $user->job = $this->job;
        $user->activity = $this->activity;
        $user->interests = $this->interests;
        $user->study = $this->study;
        return $user->save(false);
    }
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['firstname', 'lastname', 'birthday', 'sex', 'country', 'city_id', 'relationship'], 'required'],
            [['phone', 'relationship', 'job', 'activity', 'interests', 'study'], 'string'],
            [['firstname', 'lastname'], 'string', 'max' => 50],
            [['city_id'], 'integer'],
            [['sex'], 'string', 'max' => 10],
            [['country'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'firstname' => Yii::t('common', 'Имя'),
            'lastname' => Yii::t('common', 'Фамилия'),
            'birthday' => Yii::t('common', 'Дата рождения'),
            'sex' => Yii::t('common', 'Пол'),
            'country' => Yii::t('common', 'Страна'),
            'city_id' => Yii::t('common', 'Город'),
            'relationship' => Yii::t('common', 'Семейное положение'),
            'phone' => Yii::t('common', 'Телефон'),
            'job' => Yii::t('common', 'Работа'),
            'activity' => Yii::t('common', 'Деятельность'),
            'interests' => Yii::t('common', 'Интересы'),
            'study' => Yii::t('common', 'Учеба'),
        ];
    }
}
