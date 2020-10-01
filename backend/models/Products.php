<?php

namespace backend\models;

use Yii;
use common\models\User;
use yii\helpers\Url;

/**
 * This is the model class for table "tbl_products".
 *
 * @property int $id
 * @property string $name
 * @property int $price
 * @property int $created_by
 * @property string $created_on
 * @property int $updated_by
 * @property string $updated_on
 *
 * @property TblProductsImages[] $tblProductsImages
 */
class Products extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tbl_products';
    }

     public function behaviors()
    {
        return [
            [
                'class' => \yii\behaviors\BlameableBehavior::className(),
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'price', ], 'required'],
            ['name','unique'],
            [['name'], 'string'],
            [['price'], 'integer'],
            [['created_on', 'updated_on'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'price' => 'Price',
            'created_by' => 'Created By',
            'created_on' => 'Created On',
            'updated_by' => 'Updated By',
            'updated_on' => 'Updated On',
        ];
    }

    /**
     * Gets query for [[TblProductsImages]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProductImages()
    {
        return $this->hasMany(ProductsImages::className(), ['product_id' => 'id']);
    }

    public function getUser(){
        return $this->hasOne(User::className(),["id"=>"created_by"]);
    }


    /*@Desc : Get All Production from tbl_products table for api
     *@retrun array : array of products 
     */
    public function getProducts(){

        $ret = [];
        $products = Products::find()->alias("p")->select("p.id,p.name,p.price")
                    ->joinWith(["productImages"])
                    ->asArray()
                    ->all();
        
        if($products){
            $ret["image_base_url"] = Url::base(true)."/uploads/";
            $ret["products"] = $products;
        }                    
        return $ret;            
    }
}
