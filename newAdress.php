<?php

  require "database.php";

  session_start();
  
  // En caso de que quiera ingresar y la session no haya hecho el login
  if(!isset($_SESSION["user"])){
    header("Location: login.php");
    return;
  }

  $error = null;
    
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      if (empty($_POST["tag"]) ||empty($_POST["street"]) ||empty($_POST["number"])||empty($_POST["departament"])||empty($_POST["floor"]) || empty($_POST["city"]) || empty($_POST["country_state"]) || empty($_POST["country"]) || empty($_POST["zip"])) {
        $error = "Please fill all the fields.";
       }  
       else {
        // Definimos las variables a insertar
        $tag = $_POST['tag'];
        $street = $_POST['street'];
        $num = $_POST['number'];
        $departament = $_POST['departament'];
        $floor = $_POST['floor'];
        $city = $_POST["city"];
        $state = $_POST["country_state"];
        $country = $_POST["country"];
        $zip = $_POST["zip"];


        // Insertamos las variables con el contenido 
        $statement = $conn->prepare("INSERT INTO adress (tag, street, num, departament, floor, city, country_state, country, zip, contact_id, description) VALUES (:tag, :street, :num, :departament, :floor, :city, :country_state, :country, :zip, :contact_id, :description )");
        $statement->bindParam(":tag", $_POST["tag"]);
        $statement->bindParam(":street", $_POST["street"]);
        $statement->bindParam(":num", $_POST["number"]);
        $statement->bindParam(":departament", $_POST["departament"]);
        $statement->bindParam(":floor", $_POST["floor"]);
        $statement->bindParam(":city", $_POST["city"]);
        $statement->bindParam(":country_state", $_POST["country_state"]);
        $statement->bindParam(":country", $_POST["country"]);
        $statement->bindParam(":zip", $_POST["zip"]);
        $statement->bindParam(":contact_id", $_POST["id"]);
        $statement->bindParam(":description", $_POST["description"]);
        $statement->execute();

        $_SESSION["flash"] = ["message" => "Adress added."];

        header("Location: home.php");
        return;
      }
    }
  $contacts = $conn->query("SELECT * FROM contacts WHERE user_id = {$_SESSION['user']['id']}");
?>

  <?php require "partials/header.php" ?>
    <div class="container pt-5">
      <div class="row justify-content-center">
        <div class="col-md-8">
          <div class="card">
            <div class="card-header">Add New Adress</div>
            <div class="card-body">
              <?php if ($error): ?>
                <p class="text-danger">
                  <?= $error ?>
                </p>
              <?php endif ?>
              <form method="POST" action="newAdress.php">
              <form class="needs-validation" novalidate>
                <class="form-row">
                <div class="col-auto my-1">
                  <label class="mr-sm-2" for="inlineFormCustomSelect">Choose your contact...</label>
                  <select class="custom-select mr-sm-2" id="id" name="id">
                    <option selected></option>
                    <!-- Mostratmos los posibles contactos a los cuales asignar la variables -->
                    <?php foreach ($contacts as $contact): ?>
                    <option value=<?=$contact['id']?>><?=$contact['name']?></option>
                    <?php endforeach ?>
                  </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="tag">Adress Tag</label>
                    <input type="text" class="form-control" id="tag" name="tag" required>
                </div>
                <div class="col-md-6 mb-3">
                  <label for="street">Street</label>
                  <input type="text" class="form-control" id="street" name="street" required>  
                </div>

                <div class="col-md-6 mb-3">
                  <label for="number">Number</label>
                  <input type="text" class="form-control" id="number" name="number" required>
                </div>

                <div class="col-md-6 mb-3">
                  <label for="departament">Departament</label>
                  <input type="text" class="form-control" id="departament" name="departament">
                </div>

                <div class="col-md-6 mb-3">
                  <label for="floor">Floor</label>
                  <input type="text" class="form-control" id="floor" name="floor">
                </div>
                  

                <div class="col-md-6 mb-3">
                  <label for="city">City</label>
                  <input type="text" class="form-control" id="city" name="city" required>
                </div>
                <div class="col-md-3 mb-3">
                  <label for="state">State</label>
                  <input type="text" class="form-control" id="country_state" name="country_state" required>
                </div>
                <div class="col-md-6 mb-3">
                  <label for="country">Country</label>
                  <input type="text" class="form-control" id="country" name="country" required>
                </div>
                <div class="col-md-3 mb-3">
                  <label for="zip">Zip</label>
                  <input type="text" class="form-control" id="zip" name="zip" required>
                </div>
              
                <div class="col-md-6 mb-3">
                  <label for="description">Description</label>
                  <input type="text" class="form-control" id="description" name="description">
                </div>
                <button class="btn btn-primary" type="submit">Submit form</button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
    
<?php require "partials/footer.php" ?>
