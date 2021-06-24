<?php
session_start();


if ((isset($_COOKIE['id'])) && (!isset($_SESSION["loggedUser"]))) {
  $id = $_COOKIE['id'];
  require_once 'model/M_connection.php';
  $dbConn = new Connection();
  $conn = $dbConn->connect();
  require_once 'model/M_user.php';
  $userTemp = new User();
  $userTemp->preencher($conn, $id);

  $result = $userTemp->searchLogin($conn);
  if ($result->num_rows > 0) {
    //LOGADO
    $user = $result->fetch_assoc();


    if (isset($_SESSION["loggedAdmin"])) {
      unset($_SESSION["loggedAdmin"]);
      unset($_SESSION["idAdmin"]);
    }

    $_SESSION["loggedUser"] = True;
    $_SESSION["idUser"]     = $user["id"];
    $_SESSION["nameUser"]   = aes_256("decrypt", $user["name"]);
  }
}
?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="">
  <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
  <meta name="generator" content="Hugo 0.83.1">
  <title>PIBES - GMS</title>

  <!-- Bootstrap core CSS -->
  <link href="lib/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- CSS para ícones -->
  <link href="lib/open-iconic/font/css/open-iconic.css" rel="stylesheet">

  <!-- Custom styles for this template -->
  <link href="lib/bootstrap/dist/css/carousel.css" rel="stylesheet">

  <!-- CSS temporário para os placeholders (imagens em cinza) -->
  <style>
    .bd-placeholder-img {
      font-size: 1.125rem;
      text-anchor: middle;
      -webkit-user-select: none;
      -moz-user-select: none;
      user-select: none;
    }

    @media (min-width: 768px) {
      .bd-placeholder-img-lg {
        font-size: 3.5rem;
      }
    }

    .img-carousel {
      /*height: 100%;*/
      height: 30.5vh;
      object-fit: contain;
    }
  </style>

</head>

<body class="mt-0 mb-0 pt-0 pb-0">

  <?php include "view/header.php"; ?>

  <main>

    <div id="carousel_1" class="carousel slide" data-bs-ride="carousel">
      <div class="carousel-indicators">
        <button type="button" data-bs-target="#carousel_1" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
        <button type="button" data-bs-target="#carousel_1" data-bs-slide-to="1" aria-label="Slide 2"></button>
        <button type="button" data-bs-target="#carousel_1" data-bs-slide-to="2" aria-label="Slide 3"></button>
      </div>
      <div class="carousel-inner">
        <div class="carousel-item active">
          <img src="img/loja1.gif" alt="" class="img-carousel" />
          <!-- <svg class="bd-placeholder-img" width="100%" height="100%" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" preserveAspectRatio="xMidYMid slice" focusable="false">
            <rect width="100%" height="100%" fill="#777" />
          </svg> -->
        </div>
        <div class="carousel-item">
          <img src="img/loja1.gif" alt="" class="img-carousel" />
        </div>
        <div class="carousel-item">
          <img src="img/loja1.gif" alt="" class="img-carousel" />
        </div>
      </div>
      <button class="carousel-control-prev" type="button" data-bs-target="#carousel_1" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
      </button>
      <button class="carousel-control-next" type="button" data-bs-target="#carousel_1" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
      </button>
    </div>


    <!-- SOBRE NÓS -->
    <div class="container" id="sobre">

      <hr class="featurette-divider">

      <div class="row featurette">
        <div class="col-md-4 order-md-1">
          <svg class="bd-placeholder-img bd-placeholder-img-lg featurette-image img-fluid mx-auto" width="400" height="400" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: 500x500" preserveAspectRatio="xMidYMid slice" focusable="false">
            <title>Placeholder</title>
            <rect width="100%" height="100%" fill="#eee" /><text x="50%" y="50%" fill="#aaa" dy=".3em">400x400</text>
          </svg>
        </div>
        <div class="col-md-8 order-md-2">
          <h1 class="mt-4">Sobre Nós</h1>
          <br>
          <p class="lead">A GMS atua na área de representação de moda desde 2008, criou sua própria linha em 2012, com
            foco na moda feminina.
            Após isso, a pedidos de empresas e parceiros começamos a comercializar uniformes corporativos para alguns
            grupos e segmentos.
            Atualmente trabalhamos exclusivamente com as linhas de uniformes profissionais e executivos. Nos destacamos
            pela qualidade, pontualidade e inovação em nossas confecções.</p>
          <p class="lead">A GMS é uma empresa familiar, comprometida com o bem-estar social e com a preservação e
            conservação ambiental. Nossos ideais são embasados primeiramente em Deus, na família e na excelência.</p>
        </div>
      </div>

      <hr class="featurette-divider">

    </div><!-- /SOBRE NÓS -->

    <div class="container-fluid bg-info text-center py-5 mt-5 mb-5" id="servicos">
      <p class="h1 text-white">Serviços</p>
    </div>

    <!-- ALBUM DE SERVIÇOS -->
    <div class="album">
      <div class="container">

        <div class="py-4 text-center">
          <p class="lead">Oferecemos aos nossos clientes as melhores matérias-primas do mercado, possuímos um rigoroso
            controle de qualidade, entregando uniformes de alto padrão. Conheça nossas linhas!</p>
        </div>

        <div class="row w-75 m-auto row-cols-1 row-cols-sm-2 row-cols-md-2 g-2">
          <div class="col">
            <div class="card shadow-sm h-100">
              <svg class="bd-placeholder-img card-img-top" width="100%" height="225" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: Thumbnail" preserveAspectRatio="xMidYMid slice" focusable="false">
                <title>Placeholder</title>
                <rect width="100%" height="100%" fill="#55595c" /><text x="50%" y="50%" fill="#eceeef" dy=".3em">Thumbnail</text>
              </svg>
              <div class="card-body d-flex flex-column">
                <h3 class="text-info py-2">Uniformes Profissionais</h3>
                <p class="card-text">Confeccionamos uniformes conforme a necessidade de nossos clientes, estamos
                  preparados com uma linha completa para atender todos os tipos de segmentos empresariais.</p>
                <div class="justify-content-between align-items-center mt-auto">
                  <button type="button" class="btn btn-sm w-100 btn-info">Veja Mais</button>
                </div>
              </div>
            </div>
          </div>
          <div class="col">
            <div class="card shadow-sm h-100">
              <svg class="bd-placeholder-img card-img-top" width="100%" height="225" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: Thumbnail" preserveAspectRatio="xMidYMid slice" focusable="false">
                <title>Placeholder</title>
                <rect width="100%" height="100%" fill="#55595c" /><text x="50%" y="50%" fill="#eceeef" dy=".3em">Thumbnail</text>
              </svg>
              <div class="card-body d-flex flex-column">
                <h3 class="text-info py-2">Uniformes Executivos</h3>
                <p class="card-text">Com uma linha refinada e atualizada desenvolvemos modelos exclusivos atendendo
                  todas as áreas de sua empresa, seja escritório, comercial ou executiva.</p>
                <div class="justify-content-between align-items-center mt-auto">
                  <button type="button" class="btn btn-sm w-100 btn-info">Veja Mais</button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div> <!-- /.Album SERVIÇOS -->

    <div class="container-fluid bg-info text-center py-5 mt-5 mb-5">
      <h1 class="py-5 text-white">Qualidade e inovação em nossos serviços e produtos.</h1>
    </div>



    <!-- QUADRADOS DE PARCEIROS -->

    <div class="text-center" id="parceiros">
      <h2>Parceiros</h2>
    </div>

    <div class="container marketing">

      <hr>

      <div class="row py-5">
        <div class="col-lg-4">

          <img class="bd-placeholder-img " width="180" height="180" src="img/logo2.png" />



        </div><!-- /.col-lg-4 -->
        <div class="col-lg-4">
          <img class="bd-placeholder-img " width="180" height="180" src="img/logo3.jpeg" />


        </div><!-- /.col-lg-4 -->
        <div class="col-lg-4">
          <img class="bd-placeholder-img " width="180" height="180" src="img/logo-s.png" />



        </div><!-- /.col-lg-4 -->
      </div><!-- /.ROW DE PARCEIROS -->
    </div>

    <div class="container w-75" id="contato">

      <h2 class="text-center">Contato</h2>
      <hr class="mb-4">
      <div class="row m-auto row-cols-1 row-cols-sm-1 row-cols-md-2 g-2">
        <div class="col">
          <form>
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-2 g-2">
              <div class="col">
                <div class="form-floating">
                  <input type="text" class="form-control" id="nome" placeholder="Nome Completo">
                  <label for="nome">Nome</label>
                </div>
              </div>
              <div class="col">
                <div class="form-floating">
                  <input type="email" class="form-control" id="email" placeholder="name@example.com">
                  <label for="email">Email</label>
                </div>
              </div>
            </div>
            <div class="form-floating mt-2">
              <input type="text" class="form-control" id="senha" placeholder="Password">
              <label for="senha">Assunto</label>
            </div>
            <div class="form-floating mt-2">
              <textarea class="form-control" style="height: 100px;" aria-label="With textarea" id="mensagem"></textarea>
              <label for="mensagem">Mensagem</label>
            </div>

            <button class="w-100 btn btn-lg btn-primary mt-3" type="submit">Enviar</button>
          </form>
        </div>
        <div class="col">
          <div class="container w-75">
            <p class="py-3"><span class="oi" data-glyph="map-marker"></span> Rua Otavio Claudino de Camargo 320 -
              Cruzeiro - SJP - PR</p>

            <p class="py-3"><span class="oi" data-glyph="phone"></span> (41)99924-0138 - (41) 3383-3637</p>

            <p class="py-3"><span class="oi" data-glyph="envelope-closed"></span> gmsrepres@hotmail.com</p>
          </div>
        </div>
      </div>

      <hr class="mt-5 mb-5">
    </div>

    <!-- POPUP ALERTA DE CONSENTIMENTO -->

    <div id="lawmsg" class="container alert alert-info alert-dismissible h6 fade show fixed-bottom" role="alert">
      <div class="row">
        <div class="col-11">

          We use cookies on this website to distinguish you from other users. We use this data to enhance your experience and for targeted advertising. &nbsp; By continuing to use this website you consent to our use of cookies. &nbsp; For more information, please see our &nbsp;
          <a href="privacy.html" target="_blank">Cookie Policy</a>.
          <br>
        </div>
        <div class="col-1">
          <button id="btn-cookie-accept" type="button" class="btn w-100 btn-sm btn-info">Aceitar</button>

          <button id="btn-cookie-close" type="button" class="btn-close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true"></span>
          </button>
        </div>
      </div>
    </div>
    </div>
    <!---->
    <script>
      var d = document
      d.getElementById('btn-cookie-accept')
        .addEventListener('click', () => {
          d.getElementById('lawmsg').hidden = true
        });
      d.getElementById('btn-cookie-close')
        .addEventListener('click', () => {
          d.getElementById('lawmsg').hidden = true
        });
    </script>

  </main>

  <!-- FOOTER -->
  <?php include "view/footer.php"; ?>

  <script src="lib/bootstrap/dist/js/bootstrap.bundle.min.js"></script>


</body>

</html>