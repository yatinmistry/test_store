<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Products */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="products-form">

    <div class="row">
    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <div class="col-md-6">
        <?= $form->field($model, 'name')->textInput(['rows' => 6]) ?>

        <?= $form->field($model, 'price')->textInput() ?>

        <?= $form->field($modelImages, 'imageFiles[]')
                 ->fileInput(['multiple' => true, 'accept' => 'image/*'])
                 ->label("Images") ?>
        
        <?php if($model->id){
                echo $this->render("_product_images",[
                    "model"=>$model,
                    "view"=>"update",
                ]);
            }
        ?>
        <div class="form-group">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
    </div>
</div>
