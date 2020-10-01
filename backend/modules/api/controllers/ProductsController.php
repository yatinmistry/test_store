<?php

namespace backend\modules\api\controllers;

use Yii;
use yii\helpers\ArrayHelper;
use backend\models\Products;
use backend\models\Cart;

class ProductsController extends BaseController
{

	public function behaviors()
    {

        $behaviors = parent::behaviors();

        $behaviors["verbs"] =  [
                'class' => \yii\filters\VerbFilter::className(),
                'actions' => [
                    'add-to-cart' => ['POST'],
                ],
            ];

        return $behaviors;    
    }

    /*@Desc : Get Product List API 
     *@API URL : http://localhost:81/test_store/backend/web/index.php/api/products/get-products
     */
    public function actionGetProducts(){

        try {
            $products = Products::getProducts();
            if( $products){
                return $this->apiResponse(200, true, $products, "Products retrieved successfully",true);    
            }
            else{
                return $this->apiResponse(200, false, $products, "No Products found",true);    
            }

        } catch (\Exception $e) {
            return $this->apiResponse(200, false, $data=[], "Products retrieved failed - ".$e->getMessage(),true);
            
        }
        return $response;
    }

    /*
     @Desc: Add To Cart API 
     @API URL: http://localhost:81/test_store/backend/web/index.php/api/products/add-to-cart
     */
    public function actionAddToCart(){

        try {

            $request = Yii::$app->request->post();
            
            $cartModel = new Cart();
            $res = $cartModel->addToCart($request);

            if($res){
                return $this->apiResponse(200, true, $data=[], "Product successfully added to cart",true);    
            }else{
                $errors = setYiiErrors($cartModel->errors,true);
                return $this->apiResponse(200, false, $data=[], "Add to cart failed. Error - ".$errors,true);
            }

        } catch (\Exception $e) {
            return $this->apiResponse(200, false, $data=[], "Add to cart failed. Error - ".$e->getMessage(),true);
            
        }
        return $response;
    }

	
}