<?php

namespace backend\modules\api\controllers;

use Yii;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;

class BaseController extends \yii\rest\Controller
{

	public function init(){

		$response = Yii::$app->response;
		$response->format = \yii\web\Response::FORMAT_JSON;
		$response->charset = "UTF-8";

		Yii::$app->request->parsers['application/json'] = 'yii\web\JsonParser';

		return parent::init();
	}


    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();


        // $behaviors['authenticator'] = [
        //     'class' => \yii\filters\auth\HttpBearerAuth::className(),
        // ];

        $behaviors["basicAuth"] = [
            'class' => \yii\filters\auth\HttpBasicAuth::className(),
            'auth' => function ($username, $password) {
                $user = \common\models\User::find()->where(['username' => $username])->one();
                if ($user->validatePassword($password)) {
                    return $user;
                }
                return null;
            },
        ];

        return $behaviors;
    }


	/** 
	 * // Todo Changed response format
     * Api response
     */
    public function apiResponseBits($httpstatus, $status, $data = [], $message = '')
    {
        Yii::$app->response->statusCode = $httpstatus; 

        if (!empty($message))
        {
            if (is_array($message)){				
                $message = implode(", ",$message);
            }
            $response['message'] = ucwords($message);
        }

        $response = [
            'code' => $status,
            'status'=> $status == 3000 ? false: true,
            'data' => $data,
            'message'=> $message,
        ];

        return $response;
    }


    public function apiResponse($httpstatus, $status, $data = [], $message = '',$mergeData=false)
    {
        Yii::$app->response->statusCode = $httpstatus; 

        if (!empty($message)){
            if (is_array($message)){                
                $message = implode(", ",$message);
            }
            $response['message'] = ucwords($message);
        }

        $response = [
            'status'=> $status,            
            'message'=> $message,
        ];
        
       // if($mergeData){
           // $response = array_merge($response,$data);
       // }else{          
        $response['data'] = $data;  
       // }

        return $response;
    }
    

}