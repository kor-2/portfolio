<?php
//require_once 'compte.php';
require_once 'config/framework.php';
require_once 'config/connect.php';

$errors = [];

if (isset($_POST['token']) && $_POST['token'] === $_SESSION['token']) {

  if (isset($_POST['email']) && !preg_match('#^[\w.-]+@[\w.-]+.[a-z]{2,6}$#i', $_POST['email'])) {
    $errors['email'] = 'Adresse email non valide';
  }

  if (empty($errors)) {
    $sql = "SELECT * FROM users WHERE email = '".$_POST['email']."'";
    if ($result = $mysqli->query($sql)) {
      if ($result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
              if (password_verify($_POST['password'], $row['password']) === true) {
                $_SESSION['user'] = $row;
                redirectToRoute('/compte.php');
              } else {
                $errors['compte'] = 'compte non reconnu';
              }
          }
      } else {
        $errors['email'] = 'Adresse email non valide';
      }
      $result->close();
    }
  }
}

?>

<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1, shrink-to-fit=no"
    />

    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css"
    />

    <title>Se connecter</title>
  </head>
  <body>
    <div>
    <div class="m-a">
      <div class="col-md-10 col-lg-6 col-xl-5 order-2 order-lg-1 m-auto">
        <p class="text-center h1 fw-bold mb-5 mx-1 mx-md-4 mt-4">
          Se connecter
        </p>

        <?= isset($errors['compte']) ? $errors['compte'] : ''; ?>
        <form class="mx-1 mx-md-4" method="post" >
          <input type="hidden"name="token" value="<?= miniToken(); ?>">
          

          <div class="d-flex flex-row align-items-center mb-4">
            <i class="fas fa-envelope fa-lg me-3 fa-fw"></i>
            <div class="form-outline flex-fill mb-0">
              <input type="email"  class="form-control" name="email" id="email"/>
              <label class="form-label" for="email">E-mail</label>
              <?= isset($errors['email']) ? $errors['email'] : ''; ?>
            </div>
          </div>

          <div class="d-flex flex-row align-items-center mb-4">
            <i class="fas fa-lock fa-lg me-3 fa-fw"></i>
            <div class="form-outline flex-fill mb-0">
              <input type="password"  class="form-control"  name="password" id="password"/>
              <label class="form-label" for="password">Mot de passe</label>
            </div>
          </div>

          <div class="d-flex justify-content-center m-4">
            <button type="submit" id="submit" name="submit" class="btn btn-primary ">S'inscrire</button>
          </div>
          
        </form>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>