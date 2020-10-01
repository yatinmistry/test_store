<?php

namespace backend\controllers;

use Yii;
use backend\models\Products;
use backend\models\ProductsImages;
use backend\models\ProductsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\models\ProductsImagesUpload;
use yii\web\UploadedFile;

/**
 * ProductsController implements the CRUD actions for Products model.
 */
class ProductsController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Products models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ProductsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Products model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Products model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Products();
        $modelImages = new ProductsImagesUpload();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            $modelImages->imageFiles = UploadedFile::getInstances($modelImages, 'imageFiles');
            if ($modelImages->upload($model)) {
                // file is uploaded successfully
                // return;
            }
            Yii::$app->session->setFlash("success","Product added successfully");
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
            'modelImages' => $modelImages,
        ]);
    }

    /**
     * Updates an existing Products model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
         $modelImages = new ProductsImagesUpload();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            $modelImages->imageFiles = UploadedFile::getInstances($modelImages, 'imageFiles');
            if ($modelImages->upload($model)) {
                // file is uploaded successfully
                // return;
            }
            Yii::$app->session->setFlash("success","Product updated successfully");
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
             'modelImages' => $modelImages,
        ]);
    }

    /**
     * Deletes an existing Products model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Products model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Products the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Products::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }


    public function actionList(){

        $model = new Products();
        $data = $model->getProducts();
        return $this->render("product_list",[
            "data"=>$data
        ]);
    }


    public function actionDeleteImage($id,$image_id,$redirect){

        $imageModel = ProductsImages::findOne($image_id);
        $imagePath = Yii::getAlias('@uploads')."/".$imageModel->image_name;
        unlink($imagePath);
        $imageModel->delete();

        Yii::$app->session->setFlash("success","Image Deleted successfully");

        return $this->redirect([$redirect,"id"=>$id]);
    }
}
