<?php
include("./_inc/db.php");
$con = connect();
$query = "SELECT `articole`.`nume`, `articole`.`poza`, `articole`.`descriere`, `articole`.`continut`, `articole`.`data_adaugare`,
        `articole`.`id_categorie`, `categorii`.`id` as `id_categorie`, `categorii`.`nume` as `nume_categorie`
        FROM `articole`
        LEFT JOIN `categorii` ON `articole`.`id_categorie` = `categorii`.`id`
        WHERE `articole`.`id`='".$_GET["id"]."'";

$result = queryactive($con, $query);
$articol = getRow($result);
$sql1= "SELECT  `galerie`.`titlu`, `galerie`.`poza`, `galerie`.`id`, `galerie`.`status`
FROM `galerie`
LEFT JOIN `articole` ON `galerie`.`id_articol` = `articole`.`id` 
WHERE `articole`.`id` = '".$_GET["id"]."'";
// echo $sql1;

$result1 = queryactive($con, $sql1);
// var_dump($result1);
$galerie = getArray($result1);
// var_dump($galerie);
// foreach($galerie as $key => $galery){
//   echo "Key = ".$key."<br/>";
// }
// exit();
$id_articol = $_GET["id"];
if(isset($_POST) && !empty($_POST)){
  $query1 = "INSERT INTO `comentarii` SET
         `comentarii`.`username`='".$_POST["username"]."',
         `comentarii`.`continut`='".$_POST["continut"]."',
         `comentarii`.`id_articol`='".$id_articol."'";
  queryactive($con, $query1);
}
$query2 = "SELECT `comentarii`.`username`, `comentarii`.`continut`, `comentarii`.`id_articol`, `comentarii`.`status`
          FROM `comentarii`
          WHERE `id_articol`='".$id_articol."'
          AND `comentarii`.`status` = '1'";
$rezultat = queryactive($con, $query2);
$comentarii = getArray($rezultat);
// var_dump($comentarii);
$meta_name = $articol["nume"];
$meta_description = $articol["descriere"];
$meta_image = $articol["poza"];
$title = $articol["nume"];
  include("./header.php"); 
?>

    <!-- Page Content -->
    <div class="container">

      <div class="row">

        <!-- Post Content Column -->
        <div class="col-lg-12">

          <!-- Title -->
          <h1 class="mt-4 page-title text-josefin-style transform-uppercase text-center"><?php echo $articol["nume"]; ?></h1>

          <!-- Meta text -->
          <div class="entry-meta">
              <a href="./articol.php?id=<?php echo $articol["id"]; ?>#comment-section" class="meta-option light-text"><i class="far fa-comment"></i> Comments </a>
              <a href="./index.php?categorie_id=<?php echo $articol["id_categorie"]; ?>" class="meta-option light-text"><i class="far fa-bookmark"></i> <?php echo $articol["nume_categorie"]; ?></a>
          </div>

          <hr>

          <!-- Preview Image -->
          <?php
          if(is_file("./public/images/".$articol["poza"]) && !empty($articol["poza"])){
            $articol_poza = $articol["poza"];
          } else{
            $articol_poza = "default.jpg";
          }
          ?>

          <div class="image-preview-article"style="background-image: url('./public/images/<?php echo $articol_poza; ?>');"> </div>
         

          <!-- Post Content -->
         <p> <?php echo nl2br($articol["continut"]); ?> </p>
       
          <!-- Carousel -->
       
         <div id="myCarousel" class="carousel slide" data-ride="carousel">
            <!-- Indicators -->
          <ol class="carousel-indicators">
          <?php 
          for( $i = 0; $i < count($galerie); $i++ ){
            if( $i == 0 ){
              $active = "active";
            }else{
              $active = "";
            }
          ?>
            <li data-target="#myCarousel" data-slide-to="<?php echo $i; ?>" class="<?php echo $active; ?>"></li>
          <?php  } ?>
          </ol>
          <!-- Wrapper for slides -->
          <div class="carousel-inner">
            <?php foreach ($galerie as $key => $picture){
              if( $key == 0 ){
                $active = "active";
              }else{
                $active = "";
              }
              ?>
              <div class="carousel-item <?php echo $active; ?> ">
                <img src="./public/images/<?php echo $picture["poza"]; ?>" class="d-block w-100" alt="Chicago">
                <div class="carousel-caption d-none d-md-block">
                  <h3><?php echo $picture["titlu"]; ?></h3>
                </div>
              </div>
            <?php } ?>
          
   
          </div>

          <!-- Left and right controls -->
          <a class="carousel-control-prev" href="#myCarousel" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
          </a>
          <a class="carousel-control-next" href="#myCarousel" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
          </a>
        </div>
        <!-- end carusel -->


          <!-- Comments Form -->
          <div class="my-4">
            <div class="card-body">
              <form action="./articol.php?id=<?php  echo $id_articol; ?>" method="post" class="form-comments">
                <div class="comments-form-header">
                  <h5 class="text-center">Leave a Comment</h5>
                </div>
                <div class="form-group">
                  <input type="text" name="username" class="form-control" id="nume" placeholder="Name" required>
                </div>
                <div class="form-group">
                  <textarea class="form-control" name="continut" id="continut" placeholder="Comment" rows="5" required></textarea>
                </div>
                <div class="d-flex">
                  <button type="submit" class="btn btn-custom mx-auto mt-3">Post Comment</button>
                </div>
              </form>
            </div>
          </div>
          <hr>
          <div class="d-flex justify-content-center comments-title text-josefin-style">
            <span> <?php echo count($comentarii); ?> Comments </span>
          </div>
          
          <div id="comment-section">
            <!-- Single Comment -->
            <?php foreach($comentarii as $key => $comentariu){

            ?>
            <div class="media mb-4 comment">
              <div class="media-body">
                <h5 class="mt-0 comments-username"><?php echo $comentariu["username"]; ?></h5>
                <?php echo $comentariu["continut"]; ?>
              </div>
            </div>
            <?php
            }
            ?>
          </div>

        </div>

      </div>
      <!-- /.row -->

    </div>
    <!-- /.container -->

    <!-- Footer -->
    <?php include("./footer.php"); ?>
    
    <!-- Bootstrap core JavaScript -->
    <script src="./public/vendor/jquery/jquery.min.js"></script>
    <script src="./public/js/bootstrap.min.js"></script>

  </body>

</html>
