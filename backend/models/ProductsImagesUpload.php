<?php 
namespace backend\models;

use yii\base\Model;
use yii\web\UploadedFile;

class ProductsImagesUpload extends Model
{
    /**
     * @var UploadedFile[]
     */
    public $imageFiles;

    public function rules()
    {
        return [
            [['imageFiles'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg,jpeg', 
                'maxFiles' => 10
            ],
        ];
    }
    
    public function upload($productModel)
    {
        if ($this->validate()) { 
            
            foreach ($this->imageFiles as $file) {

                $image_name = uniqid()."-".$file->baseName . '.' . $file->extension;
                $file->saveAs('uploads/' . $image_name);

                //Save TO DB 
                   $imageModel = new ProductsImages();
                   $imageModel->product_id = $productModel->id;
                   $imageModel->image_name = $image_name;
                   $imageModel->save();
            }
            return true;
        } else {
            return false;
        }
    }
}