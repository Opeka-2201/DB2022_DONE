<?php
if (isset($_POST['username']) && isset($_POST['pwd'])) {
    if ($_POST['username']=='group25' && $_POST['pwd']=='a'){
        $_SESSION['loggedUser'] = true;
    }
    else {
        $error ='Erreur d\'authentification';
    }
}
?>
  <?php if(!isset($_SESSION['loggedUser'])): ?>
      <form class="form-signin text-center" action="index.php" method="post">
          <img class="mb-4" src="/img/uliege.png" alt="" width="200" height="auto">
          <h1 class="h3 mb-3 fw-normal">Please sign in</h1>
          <?php if(isset($error)) : ?>
              <div class="error-message" role="alert">
                  <?php echo $error;?>
              </div>
            <?php endif; ?>
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
  <?php endif; ?>