<?php 
$this->title = 'Products List';
$this->params['breadcrumbs'][] = $this->title;
?>

<style type="text/css">
    body {
    /*background: linear-gradient(to right, #c04848, #480048);*/
    /*min-height: 100vh*/
}

.text-gray {
    color: #aaa;
    margin: 3px;
}
.product-img-container{
    border: 2px solid #FFF; 
    margin:3px;
}
.product-img-container:hover{
    border: 2px solid #CCC; 
}

/*img {
    height: 170px;
    width: 140px
}*/
</style>
 <h1 class="display-4">Product List</h1>
<div class="container py-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <!-- List group-->
            <ul class="list-group shadow">
                <!-- list group item-->
                <?php if($data){
                    $baseUrl = $data["image_base_url"];
                    foreach ($data["products"] as $key => $product) {
                      
                 ?>
                <li class="list-group-item">
                    <!-- Custom content-->
                    <div class="media align-items-lg-center flex-column flex-lg-row p-3">
                        <div class="media-body order-2 order-lg-1">
                            <h5 class="mt-0 font-weight-bold mb-2"><?php echo $product["name"];?></h5>
                            <!-- <p class="font-italic text-muted mb-0 small">Desc</p> -->
                            <div class="d-flex align-items-center justify-content-between mt-1">
                                <h6 class="font-weight-bold my-2">₹<?php echo $product["price"];?></h6>
                                <ul class="list-inline small" style="padding-left: 5px;">


                                    <?php if(isset($product["productImages"]) && !empty($product["productImages"])){
                                        foreach ($product["productImages"] as $key => $productImage) {
                                        ?>
                                        <li class="list-inline-item m-0 product-img-container">
                                            <img src="<?php echo $baseUrl."/".$productImage["image_name"];?>" width="100" height="100"></li>
                                        <?php 
                                        }
                                    ?>
                                        
                                    <?php } ?>
                                </ul>
                            </div>
                        </div>
                        <!-- <img src="https://i.imgur.com/KFojDGa.jpg" alt="Generic placeholder image" width="200" class="ml-lg-5 order-1 order-lg-2"> -->
                    </div> <!-- End -->
                </li> <!-- End -->

               <?php }
                } ?>

            </ul> <!-- End -->
        </div>
    </div>
</div>