<?php

namespace app\models;

class Product extends \yii\mongodb\ActiveRecord
{
    public static function collectionName()
    {
        return 'product';
    }

    public function attributes()
    {
        // Devuelve un array con los nombres de los atributos
        return ['_id', 'name','price'];
    }

    public function rules()
    {
        return [
         //   [['name'], 'required'],
            [['name'], 'string', 'max' => 255],
            [['price'], 'number'],
         ];
    }

    public function fields()
    {
        return [
            'name',
            'price'
        ];
    }

    public function attributeLabels()
    {
        return [
            '_id' => 'ID',
            'name' => 'Name',
            'price' => 'Price',
        ];
    }
}
