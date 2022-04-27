<!-- © Group25 - Bases de Données 2022 : Projet 2-->

<!DOCTYPE html>

<?php 
  session_start();
  $message = "";
  if(isset($_POST['username']) && isset($_POST['pwd'])):
    if($_POST['username']=='group25' && $_POST['pwd']=='3uCTA8L2ID'):
      $_SESSION['user'] = 'group25';
      $_SESSION['pwd'] = '3uCTA8L2ID';
    else:
      $message = 'Erreur d\'authentification';
    endif;
  endif;
  if(isset($_SESSION['user'])):
    header("Location:home.php");
  endif;
?>

<html>
  <head>
    <meta charset="utf-8" />
    <title>Database - Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="index.css">
  </head>

  <body>
    <div class="px-4 py-5 my-5">
      <form class="form-signin text-center" action="login.php" method="POST">
        <img class="mb-4" src="./img/uliege.png" alt="" width="200" height="auto">
        <h1 class="h3 mb-3 fw-normal">Please sign in</h1>
        <?php 
        if(isset($message)) :
          echo('<div class="error-message" role="alert">');
          echo($message);
          echo('</div>');
        endif; 
        ?>
        <div class="form-floating">
          <input name="username" type="text" class="form-control" id="floatingInput" placeholder="group00">
          <label for="floatingInput">ID</label>
        </div>
        <div class="form-floating">
          <input name="pwd" type="password" class="form-control" id="floatingPassword" placeholder="Password">
          <label for="floatingPassword">Password</label>
        </div>
        <button class="w-100 btn btn-lg btn-primary" type="submit">Sign in</button>
        <p class="mt-5 mb-3 text-muted">© Group25 - 2022</p>
      </form>
    </div>
  </body>
<html>