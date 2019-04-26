<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "activity_categories".
 *
 * @property int $id
 * @property string $name_ru
 * @property string $name_en
 */
class ActivityCategories extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'activity_categories';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name_ru', 'name_en'], 'required'],
            [['name_ru', 'name_en'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name_ru' => 'Name Ru',
            'name_en' => 'Name En',
        ];
    }

    public function findCategories($params = [])
    {
        $res = [];
        if (!empty($params['get_empty']) && $params['get_empty']) $res[] = '---';
        $field = 'name_' . Yii::$app->language;
        $categories = Yii::$app->db->createCommand("SELECT id, name_ru, name_en FROM activity_categories order by $field")->queryAll();
        foreach($categories as $category) {
            $res[$category['id']] = $category[$field];
        }

        return $res;
    }

    public function getCategoryName($id)
    {
        $category = self::find()->where(['id' => $id])->one();
        if (empty($category)) return '';
        $var = 'name_' . Yii::$app->language;
        return $category->$var;
    }
}