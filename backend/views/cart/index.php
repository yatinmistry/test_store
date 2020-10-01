<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\CartSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Carts';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cart-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <!-- <p>
        <?= Html::a('Create Cart', ['create'], ['class' => 'btn btn-success']) ?>
    </p> -->

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        // 'filterModel' => $searchModel,

        'columns' => [
            // ['class' => 'yii\grid\SerialColumn'],
            'id',
            // 'product_id',
            [
                'label'=>'',
                'format'=>'raw',
                'value'=> function($model){
                    // pr($model->product->productImages);
                    if(isset($model->product->productImages) && isset($model->product->productImages[0])){
                        return  Html::img(Yii::getAlias('@web')."/uploads/".$model->product->productImages[0]->image_name,["width"=>100,"height"=>100]);
                    }else{
                        return "< No Image >";
                    }
                    
                }
            ],
            'product.name',
            'product.price',
            'quantity',
            // 'user_id',
            // 'user.username',
            'created_on',

            // ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
