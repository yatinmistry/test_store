<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "tbl_products_images".
 *
 * @property int $id
 * @property int $product_id
 * @property string $image_name
 *
 * @property TblProducts $product
 */
class ProductsImages extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tbl_products_images';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['product_id', 'image_name'], 'required'],
            [['product_id'], 'integer'],
            [['image_name'], 'string', 'max' => 255],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Products::className(), 'targetAttribute' => ['product_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'product_id' => 'Product ID',
            'image_name' => 'Image Name',
        ];
    }

    /**
     * Gets query for [[Product]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(TblProducts::className(), ['id' => 'product_id']);
    }
}
