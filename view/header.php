<?php
$logado = false;

if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

if (isset($_SESSION["loggedUser"])) {
  if ($_SESSION["loggedUser"] == True) {
    $logado = True;
    $loggedUserName = $_SESSION["nameUser"];
    $loggedUserId = $_SESSION["idUser"];
  }
}
if (isset($_SESSION["loggedAdmin"])) {
  if ($_SESSION["loggedAdmin"] == True) {
    $logado = True;
  }
}

?>
<header class="sticky-top">
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
      <a class="navbar-brand" href="index.php">GMS Uniformes</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse py-3" id="navbarCollapse">
        <!-- <ul class="navbar-nav ms-auto mb-2 mb-md-0 "> -->
        <ul class="navbar-nav ms-auto mb-2 mb-md-0 d-flex justify-content-center align-items-center">
          <?php if (isset($_SESSION["loggedAdmin"])) { ?>
            <li class="nav-item">
              <a class="nav-link active" aria-current="page" href="index.php#">Home</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="adminProduto.php">Adicionar Produtos</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="admin.php">Visualizar Pedidos</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="catalogo.php">Produtos</a>
            </li>
          <?php } else { ?>
            <li class="nav-item">
              <a class="nav-link active" aria-current="page" href="index.php#">Home</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="catalogo.php">Catálogo</a>
            </li>
            <?php if (!isset($_SESSION["loggedUser"])) { ?>
              <li class="nav-item">
                <a class="nav-link" href="index.php#sobre">Sobre</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="index.php#parceiros">Parceiros</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="index.php#contato">Contato</a>
              </li>
            <?php } ?>
          <?php } ?>

          <!-- <li class="nav-item">
              <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Localização</a>
            </li> -->

          <?php if (!$logado) { ?>
            <li class="nav-item">
              <a class="nav-link" href="userLogin.php">Entrar</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="userRegister.php">Cadastrar</a>
            </li>

            <?php } else {
            if (isset($_SESSION["loggedUser"])) { ?>
              <li class="nav-item ">
                <a class="nav-link" href="userAccount.php">Bem Vindo(a), <?= $loggedUserName ?></a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="carrinho.php"><span style="font-size:20pt" alt="carrinho" class="oi" data-glyph="cart"></span></a>
              </li>
            <?php } else { ?>
              <li class="nav-item">
                <a class="nav-link" href="#">Bem Vindo(a), <?= "Administrador" ?></a>
              </li>
            <?php } ?>
            <li class="nav-item">
              <a class="nav-link" href="control/C_logoutUser.php"><span style="font-size:20pt" aria-label="logout" alt="logout" class="oi" data-glyph="account-logout"></span></a>
            </li>
          <?php } ?>
        </ul>
      </div>
    </div>
  </nav>
</header>
<?php

//ERROR CATCHER
if (isset($_GET['erro'])) { ?>
  <div id="popup-alert" class="alert alert-info">
    <div class="d-flex justify-content-between align-items-center">
      <div>
        <?php echo $_GET['erro']; ?>
      </div>

      <div class="align-self-center">
        <button id="btn-alert-close" class="btn-close">
      </div>
    </div>
  </div>
  <script>
    var d = document
    d.getElementById('btn-alert-close')
      .addEventListener('click', () => {
        d.getElementById('popup-alert').hidden = true
      });
  </script>
<?php }

if (isset($_GET['msg'])) { ?>
  <div id="popup-msg" class="alert alert-info">
    <div class="d-flex justify-content-between align-items-center">
      <div>
        <?php echo $_GET['msg']; ?>
      </div>

      <div class="align-self-center">
        <button id="btn-msg-close" class="btn-close">
      </div>
    </div>
  </div>
  <script>
    var d = document
    d.getElementById('btn-msg-close')
      .addEventListener('click', () => {
        d.getElementById('popup-msg').hidden = true
      });
  </script>
<?php } ?>