<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="lib/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="lib/bootstrap/exemplos-do-bootstrap/sign-in/signin.css" />
   
</head>

<body>
    
    <main class="form-signin">
    <form>
        <img class="mb-4" src="/docs/{{< param docs_version >}}/assets/brand/bootstrap-logo.svg" alt="" width="72" height="57">
        <h1 class="h1 mb-3 fw-normal">Faça seu login</h1>
        <a href="index.php">Voltar</a>

        <div class="form-floating">
        <input type="email" name="userEmail" class="form-control" id="floatingInput" placeholder="nome@examplo.com">
        <label for="floatingInput">Email</label>
        </div>
        <div class="form-floating">
        <input type="password" name="userPassword" class="form-control" id="floatingPassword" placeholder="Senha">
        <label for="floatingPassword">Senha</label>
        </div>

        <div class="checkbox mb-3">
        <label>
            <input type="checkbox" value="remember-me"> Manter conectado
        </label>
        </div>
        <button class="w-100 btn btn-lg btn-primary" type="submit">Entrar</button>
        
    </form>
    </main>
</body>

</html>