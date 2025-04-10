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
$adress = $statement->fetch(PDO::FETCH_ASSOC);


if ($statement->rowCount() == 0) {
  http_response_code(404);
  echo("HTTP 404 NOT FOUND");
  return;
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

// Consulta para eliminar la direccion 
$conn->prepare("DELETE FROM adress WHERE id = :id")->execute([":id" => $id]);

$_SESSION["flash"] = ["message" => "Adress {$adress['id']} deleted."];
 

header("Location: home.php");
