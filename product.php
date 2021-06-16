<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configurar orçamento</title>

    <link href="lib/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Nunito', sans-serif;
            padding: 8rem 8rem 8rem 8rem;
        }
        .logo {
            padding: 2rem 0 2rem 0;
            display: flex;
            max-width: 100%;
            justify-content: flex-end;
        }
        #servicosBottom{
            display: none;
        }
    </style>

    <script data-require="jquery@3.1.1" data-semver="3.1.1" src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script src="script.js"></script>
   

</head>

<body>

    <div id="header">
    </div>
    <div class="logo">
        <img src="img/gmsLogo.jpeg" alt="gms Logo" width=100>
    </div>
    <h1>Personalize o seu Orçamento</h1>
    <hr>

    <h2>Adicionar produtos</h2>
        <div class="product-preview">
        <br>  
                <form action="/action_page.php">
                    <h4>Escolha o produto</h4>
                    
                        <select class="form-select" aria-label="Produto" id="descricao">
                            <option selected value="camiseta">Camiseta</option>
                            <option value="camisa">Camisa</option>
                            <option value="jaleco">Jaleco</option>
                            <option value="calca">Calça</option>
                        </select> 
                    <br>
                    <h4>Escolha o tecido</h4>
                        <select class="form-select" aria-label="prodColor" id="descricao">
                            <option selected >Algodão branco</option>
                            <option value="1">Algodão preto</option>
                            <option value="2">Algodão azul</option>
                            <option value="3">Algodão cinza</option>
                            <option value="4">Jeans azul</option>
                            <option value="5">Jeans cinza</option>
                            <option value="6">Jeans preto</option>
                            <option value="7" >Poliéster branco</option>
                            <option value="8">Poliéster preto</option>
                            <option value="9">Poliéster azul</option>
                            <option value="10">Poliéster cinza</option>
                        </select>     
                    <br>       

                <h4>Escolha o tamanho</h4>    
                           
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" id="inlineCheckbox2" value="option2" name="size">
                                <label class="form-check-label" for="inlineCheckbox2">P</label>
                            </div> 
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" id="inlineCheckbox1" value="option1" name="size">
                                <label class="form-check-label" for="inlineCheckbox1">M</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" id="inlineCheckbox2" value="option2" name="size">
                                <label class="form-check-label" for="inlineCheckbox2">G</label>
                            </div>
                <br>
                <h4>Informe a quantidade desejada</h4>
                <input type="text" name="number" class="quantity"><br>
                <br>

                <hr>
        </div>
                <h2>Adicionar serviços</h2>
                    <div>
                        <br>                              
                        <h4>Deseja adicionar bordado?</h4>   
                        <div id="servicosTop">
                            <br>
                            <span>Posicionamento</span>
                            <select class="form-select" aria-label="bordadoTop" id="bordadoTop">
                                <option selected >Peito</option>
                                <option value="1">Manga direita</option>
                                <option value="2">Manga esquerda</option>
                                <option value="3">Costas</option>
                            </select>
                            <br>
                            <span>Tamanho</span>
                            <select class="form-select" aria-label="bordadoTopSize" id="bordadoTopSize">
                                <option selected >P</option>
                                <option value="1">M</option>
                                <option value="2">G</option>
                                
                            </select> 
                        </div>
                            <!-- Para calças -->
                            <div id="servicosBottom">
                                <br>
                                <span>Posicionamento</span>
                                <select class="form-select" aria-label="bordadoBottom" id="bordadoBottom" >
                                    <option selected >Bolso direito</option>
                                    <option value="1">Bolso esquerdo</option>
                                    <option value="2">Lateral direita</option>
                                    <option value="3">Lateral esquerda</option>
                                </select> 
                                <br>
                                <span>Tamanho</span>
                                <select class="form-select" aria-label="bordadoTopSize" id="bordadoTopSize">
                                    <option selected >P</option>
                                    <option value="1">M</option>
                                    <option value="2">G</option>
                                    
                                </select> 
                            </div>
                        <br>
                        <br>

                        <h4>Deseja adicionar estampa?</h4>   
                        <div id="servicosTop2">
                            <br>
                            <span>Posicionamento</span>
                            <select class="form-select" aria-label="estampaTop" id="estampaTop">
                                <option selected >Peito</option>
                                <option value="1">Manga direita</option>
                                <option value="2">Manga esquerda</option>
                                <option value="3">Costas</option>
                            </select>
                            <br>
                            <span>Tamanho</span>
                            <select class="form-select" aria-label="estampaTopSize" id="estampaTopSize">
                                <option selected >P</option>
                                <option value="1">M</option>
                                <option value="2">G</option>
                                
                            </select> 
                        </div>
                            <!-- Para calças -->
                            <div id="servicosBottom2">
                                <br>
                                <span>Posicionamento</span>
                                <select class="form-select" aria-label="estampaBottom" id="estampaBottom" >
                                    <option selected >Bolso direito</option>
                                    <option value="1">Bolso esquerdo</option>
                                    <option value="2">Lateral direita</option>
                                    <option value="3">Lateral esquerda</option>
                                </select> 
                                <br>
                                <span>Tamanho</span>
                                <select class="form-select" aria-label="estampaTopSize" id="estampaTopSize">
                                    <option selected >P</option>
                                    <option value="1">M</option>
                                    <option value="2">G</option>
                                    
                                </select> 
                            </div>
                        <br>
                        <br>
                        
                        <h4>Selecione o tipo de costura</h4>   
                        <div class="costura">
                            <br>
                            
                            <select class="form-select" aria-label="costura" id="costura">
                                <option selected >Simples</option>
                                <option value="1">Reforçada</option>
                                
                            </select>
                            <br>
                        </div>

                        <br>
                        <br>
                        <br>
                        <button class="w-50 btn btn-lg btn-primary" type="submit">Adicionar</button>
                        </form>  
                    </div>
        
    
   
    <script src="lib/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript">
        document.getElementById('descricao').onchange = function() {
            
            var produto = document.getElementById('descricao').value;
            
            if ( produto == 'calca') {
                document.getElementById("servicosTop").style.display = "none";
                document.getElementById('servicosBottom').style.display = "initial";
                document.getElementById("servicosTop2").style.display = "none";
                document.getElementById('servicosBottom2').style.display = "initial";
            } else{
                document.getElementById("servicosTop").style.display = "initial";
                document.getElementById('servicosBottom').style.display = "none";
                document.getElementById("servicosTop2").style.display = "initial";
                document.getElementById('servicosBottom2').style.display = "none";
            }
        }   

    </script>
</body>
 

</html>