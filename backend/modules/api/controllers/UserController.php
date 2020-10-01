<?php

namespace app\modules\api\controllers;

use Yii;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\models\forms\LoginForm;

class UserController extends BaseController
{


    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login'],
                        'allow' => true,
                    ],
                ],
            ],
            "verbs" => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'login' => ['post']
                ],
            ],
        ];
    }





     public function actionLogin() {

        $dataRequest['LoginForm'] = Yii::$app->request->post();
        if (empty($dataRequest['LoginForm'])) {
            $message = 'Username and Password must be required';
            return $this->apiResponse(200, 3000, [], $message);

        } else if (empty($dataRequest['LoginForm']['username']) || empty($dataRequest['LoginForm']['password'])) {
            return $this->apiResponse(200, 3000, [], "Parameter value missing");
        }
        
        $model = new LoginForm();
        if ($model->load($dataRequest) && ($result = $model->login())) {

            $data = [
                "token" => $result->token,
            ];

            return $this->apiResponse(200, 200, $data, "Logged in successfully");
        } else {
            return $this->apiResponse(200, 3000, [], "Invalid Credential");
        }
    }
}