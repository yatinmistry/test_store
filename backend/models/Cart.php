<?php

namespace backend\models;

use Yii;
use common\models\User;

/**
 * This is the model class for table "tbl_cart".
 *
 * @property int $id
 * @property int $user_id
 * @property int $product_id
 * @property string $created_on
 */
class Cart extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tbl_cart';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'product_id','quantity'], 'required'],
            [['user_id', 'product_id','quantity'], 'integer'],
            [['created_on'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'product_id' => 'Product ID',
            'created_on' => 'Created On',
        ];
    }

    public function getProduct(){
        return $this->hasOne(Products::className(),["id"=>"product_id"]);
    }

     public function getUser(){
        return $this->hasOne(User::className(),["id"=>"user_id"]);
    }

    /*Add Product in Cart */
    public function addToCart($request){

        // Validation 
        $this->attributes = $request;
        $this->user_id = 1;
        if(!$this->validate()){
            return false;
        }

        // Validation: Check Product Exist in Product Table 
        $productModel = Products::findOne($this->product_id);
        if(!$productModel){
            $this->addError("failed","Given Product (".$this->product_id.") not found in tbl_product");
            return false;
        }

        // Check Product Already Exist. If already exist then update other wise add
        $cartProduct = Cart::find()->where(["product_id"=>$this->product_id])->one();

        if($cartProduct){
             $cartProduct->quantity = $cartProduct->quantity + $this->quantity;    
             $isSaved = $cartProduct->save();
              return $isSaved ? $cartProduct->id : false; 
        }else{
            $isSaved = $this->save(); 
             return $isSaved ? $this->id : false; 
        }
    }
}
