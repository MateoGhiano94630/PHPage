<?php

require "database.php";

session_start();


if(!isset($_SESSION["user"])){
  header("Location: login.php");
  return;
}
$id = $_GET["id"];

$contacts = $conn->query("SELECT * FROM contacts WHERE user_id = {$_SESSION['user']['id']}");
$adress = $conn->query("SELECT * FROM adress WHERE contact_id = {$id}");

?>

<?php require "partials/header.php" ?>
  <div class="container pt-4 p-3">
    <div class="row">
      <!-- Controlamos que no se haya guardado ninguna direccion -->
      <?php if ($adress->rowCount() == 0): ?>
        <div class="col-md-4 mx-auto">
          <div class="card card-body text-center">
            <p>No adresses saved yet</p>
            <a href="newAdress.php">Add One!</a>
          </div>
        </div>
      <?php endif ?>
      <!-- Mostramos algunos datos significantes de la direccion -->
      <?php foreach ($adress as $ad): ?>
        <div class="col-md-4 mb-3">
          <div class="card text-center">
            <div class="card-body">
              <h3 class="card-title text-capitalize"><?= $ad["tag"] ?></h3>
              <p class="m-2"><?= $ad["street"] ?></p>
              <p class="m-2"><?= $ad["num"] ?></p>
              <p class="m-2"><?= $ad["city"] ?></p>
              <p class="m-2"><?= $ad["country"] ?></p>
              <a href="editAdress.php?id=<?= $ad["id"] ?>" class="btn btn-secondary mb-2">Edit Adress</a>
              <a href="deleteAdress.php?id=<?= $ad["id"] ?>" class="btn btn-danger mb-2">Delete Adress</a>
            </div>
          </div>
        </div>
      <?php endforeach ?>
    </div>
  </div>
<?php require "partials/footer.php" ?>  
