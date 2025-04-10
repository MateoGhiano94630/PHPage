<?php

  require "database.php";

  session_start();


  if(!isset($_SESSION["user"])){
    header("Location: login.php");
    return;
  }


  $id = $_GET["id"];
  

  $statement = $conn->prepare("SELECT * FROM adress WHERE id = :id LIMIT 1");
  $statement->execute([":id" => $id]);

  // Obtengo los contactos del usuario que esta en la session 
  $contacts = $conn->query("SELECT * FROM contacts WHERE user_id = {$_SESSION['user']['id']}");
  
 

  if ($statement->rowCount() == 0) {
    http_response_code(404);
    echo("HTTP 404 NOT FOUND");
    return;
  }

  $adress = $statement->fetch(PDO::FETCH_ASSOC);
  
  // Filtro el contacto que tiene la direccion a editar
  foreach ($contacts as $contact) {
    if ($contact['id'] == $adress['contact_id']){
      $selected_contact = $contact;
      
    } else {
      $other_contacts[] = $contact;
    }
  }

  $contact_id = $adress["contact_id"];
  $stm = $conn->prepare("SELECT user_id FROM contacts WHERE id = :id LIMIT 1");
  $stm->execute([":id" => $contact_id]);
  $uid = $stm->fetch(PDO::FETCH_ASSOC);

  if ($uid["user_id"] !== $_SESSION["user"]["id"]){
    http_response_code(403); 
    echo("HTTP 403 UNAUTHORIZED");
    return;
  }

  $error = null;

  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      if (empty($_POST["city"]) || empty($_POST["country_state"]) || empty($_POST["country"]) || empty($_POST["zip"])) {
        $error = "Please fill all the fields.";
       }  
      else {
      // Obtenemos los nuevos valores por medio de post
      $tag = $_POST["tag"];
      $street = $_POST["street"];
      $city = $_POST["city"];
      $country = $_POST["country"];
      $state = $_POST["country_state"];
      $zip = $_POST["zip"];
      $num = $_POST["number"];
      $departament = $_POST["departament"];
      $floor = $_POST["floor"];
      $description = $_POST["description"];
      $contact_id = $_POST["contact_id"];
      
      // Preparamos la consulta de actualizacion
      $statement = $conn->prepare("UPDATE adress SET tag = :tag, street = :street, num = :num, departament = :departament, floor = :floor, city = :city, country_state = :country_state, country = :country, zip = :zip,contact_id = :contact_id, description = :description where id = :id;");
      $statement->execute([
        ":id" => $id,
        ":tag" => $tag,
        ":street" => $street,
        ":num" => $num,
        ":departament" => $departament,
        "contact_id" => $contact_id,
        ":floor" => $floor,
        ":city" => $city,
        ":country_state" => $state,
        ":country" => $country,
        ":zip" => $zip,
        ":description" => $description  
      ]);

      $_SESSION["flash"] = ["message" => "Adress updated."];

      header("Location: home.php");
    }
  }  
}
?>

<?php require "partials/header.php" ?>
  <div class="container pt-5">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card">
          <div class="card-header">Edit Adress</div>
          <div class="card-body">
            <?php if ($error): ?>
              <p class="text-danger">
                <?= $error ?>
              </p>
            <?php endif ?>
            <form method="POST" action="editAdress.php?id=<?= $adress['id'] ?>">
            <form class="needs-validation" novalidate>
                <div class="form-row">
                  <div class="col-auto my-1">
                    <label class="mr-sm-2" for="inlineFormCustomSelect">Choose your contact...</label>
                    <select class="custom-select mr-sm-2" id="contact_id" name="contact_id">
                      <option selected><?=$selected_contact['name']?></option>
                      <?php foreach ($other_contacts as $contact): ?>
                      <option value=<?=$contact['id']?>><?=$contact['name']?></option>
                      <?php endforeach ?>
                    </select>
                  </div>
                  <div class="col-md-6 mb-3">
                  <label for="tag">Adress Tag</label>
                  <input value="<?= $adress['tag'] ?>" type="text" class="form-control" id="tag" name="tag" required>
                </div>
                  <div class="col-md-6 mb-3">
                  <label for="street">Street</label>
                  <input value="<?= $adress['street'] ?>" type="text" class="form-control" id="street" name="street" required>  
                </div>
                  <div class="col-md-6 mb-3">
                  <label for="number">Number</label>
                  <input value="<?= $adress['num'] ?>" type="number" class="form-control" id="number" name="number" required>
                </div>
                  <div class="col-md-6 mb-3">
                  <label for="departament">Departament</label>
                  <input value="<?= $adress['departament'] ?>" type="text" class="form-control" id="departament" name="departament">
                </div>
                  <div class="col-md-6 mb-3">
                  <label for="floor">Floor</label>
                  <input value="<?= $adress['floor'] ?>" type="text" class="form-control" id="floor" name="floor">
                  </div>
                  <div class="col-md-6 mb-3">
                    <label for="city">City</label>
                    <input value="<?= $adress['city'] ?>" type="text" class="form-control" id="city" name="city" required>
                    <div class="invalid-tooltip">
                      Please provide a valid city.
                    </div>
                  </div>
                  <div class="col-md-3 mb-3">
                    <label for="state">State</label>
                    <input value="<?= $adress['country_state'] ?>" type="text" class="form-control" id="country_state" name="country_state" required>
                    <div class="invalid-tooltip">
                      Please provide a valid state.
                    </div>
                  </div>
                  <div class="col-md-6 mb-3">
                    <label for="country">Country</label>
                    <input value="<?= $adress['country'] ?>" type="text" class="form-control" id="country" name="country" required>
                    <div class="invalid-tooltip">
                      Please provide a valid country.
                    </div>
                  </div>
                  <div class="col-md-3 mb-3">
                    <label for="zip">Zip</label>
                    <input value="<?= $adress['zip'] ?>" type="text" class="form-control" id="zip" name="zip" required>
                    <div class="invalid-tooltip">
                      Please provide a valid zip.
                    </div>
                  </div>
                  <div class="col-md-6 mb-3">
                  <label for="description">Description</label>
                  <input value="<?= $adress['description'] ?>" type="text" class="form-control" id="description" name="description" >
                </div>
                </div>
                <button class="btn btn-primary" type="submit">Submit form</button>
              </form>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
<?php require "partials/footer.php" ?>
