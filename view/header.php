<?php
$logado = false;
session_start();

if (isset($_SESSION["loggedUser"])) {
  if ($_SESSION["loggedUser"] == True) {
    $logado = True;
    $loggedUserName = $_SESSION["nameUser"];
    $loggedUserId = $_SESSION["idUser"];
  }
}
?>
<header class="sticky-top">
  <nav class="navbar navbar-expand-md navbar-dark bg-dark">
    <div class="container">
      <a class="navbar-brand" href="#">GMS Uniformes</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse py-3" id="navbarCollapse">
        <ul class="navbar-nav ms-auto mb-2 mb-md-0">
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="#">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="index.php#sobre">Sobre</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="index.php#servicos">Serviços</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="index.php#parceiros">Parceiros</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="index.php#contato">Contato</a>
          </li>

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
          <?php } else { ?>
            <li class="nav-item">
              <a class="nav-link" href="userPage.php?id=<?= $loggedUserId ?>">Bem Vindo(a), <?= $loggedUserName ?></a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="control/C_logoutUser.php">Logout</a>
            </li>
          <?php } ?>
        </ul>
      </div>
    </div>
  </nav>
</header>