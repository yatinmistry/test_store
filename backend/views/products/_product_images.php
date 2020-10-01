<?php 
use yii\helpers\Url;
use yii\helpers\Html;

if(isset($model->productImages) && !empty($model->productImages)){ 
?>
<h1>Product Images</h1>
<div class="row">
<?php 
        // echo "<table class='table table-striped table-bordered'>";
        foreach ($model->productImages as $key => $productImage) {
            // echo "<tr><td>";
            echo "<div class='col-md-4' style='border:1px solid #CCC;margin:3px;'>";
            echo Html::img(Yii::getAlias('@web')."/uploads/".$productImage->image_name,["width"=>100,"height"=>100]);

            echo Html::a("Delete",Url::to(["delete-image","id"=>$model->id,"image_id"=>$productImage->id,"redirect"=>$view]));
            // echo "</td></tr>";
            echo "</div>";
        }
        // echo "</table>";
}else{
    echo "No Images";
}
?>
</div>