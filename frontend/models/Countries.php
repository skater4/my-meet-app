<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "countries".
 *
 * @property int $id
 * @property string $country_en
 * @property string $region_en
 * @property string $city_en
 * @property string $country
 * @property string $region
 * @property string $city
 */
class Countries extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'countries';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['country_en', 'region_en', 'city_en', 'country', 'region', 'city'], 'required'],
            [['country_en', 'region_en', 'city_en', 'country', 'region', 'city'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'country_en' => 'Country En',
            'region_en' => 'Region En',
            'city_en' => 'City En',
            'country' => 'Country',
            'region' => 'Region',
            'city' => 'City',
        ];
    }

    public function getCountries()
    {
        $res = [];
        $field = 'country';
        if (Yii::$app->language == 'en') $field .= '_en';
        $countries = Yii::$app->db->createCommand("SELECT id, country, country_en FROM countries group by $field order by $field")->queryAll();
        foreach($countries as $country) {
            $res[$country['country']] = $country[$field];
        }
        return $res;
    }

    public function getCities($country)
    {
        $res = [];
        $field = 'city';
        if (Yii::$app->language == 'en') $field .= '_en';
        $cities = Yii::$app->db->createCommand("SELECT id, city, city_en FROM countries where country = '$country' or country_en = '$country' group by $field order by $field")->queryAll();
        foreach($cities as $city) {
            $res[$city['id']] = $city[$field];
        }
        //print_r($res);die();
        return $res;
    }

    public function getByCityId($city_id)
    {
        return static::find()->where(['id' => $city_id])->one();
    }
}