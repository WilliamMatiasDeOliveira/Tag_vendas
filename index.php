<?php
require("vendor/autoload.php");

$login = new Core\Login();
echo $login->processa_login();

$pdo = new Core\Conexao();


?>



<?php include('header.php'); ?>

<div class="container col-4">

    <form method="post">

        <label for="login" class="form-label">Login</label>
        <input type="text" id="login" name="login" class="form-control" required autocomplete="off">

        <br>

        <label for="senha" class="form-label">Senha</label>
        <input type="password" id="senha" name="senha" class="form-control" required autocomplete="new-password">

        <br>

        <p><a href="cad_user.php">Ainda Não Tem Uma Conta ?</a></p>

        <input type="submit" class="btn btn-primary form-control" value="Entrar">

    </form>
    <br>

    <?php

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        if (!empty($_POST['login']) && !empty($_POST['senha'])) {

            //VEJA A EXPLICAÇÃO DA FUNÇÃO COM MAIS DETALHES EM README/função_trata_dados_classe_Conexao.php

            $tratamentoLogin = $pdo->trata_dados($_POST['login'], null);
            $login = $tratamentoLogin['data_text'];

            $tratamentoSenha = $pdo->trata_dados(null, $_POST['senha']);
            $senha = $tratamentoSenha['hashed_password'];


            $data = [
                'login' => $login,
                'senha' => $senha
            ];
        }

        $pdo->insert('usuarios', $data);

    }

    ?>

    
</div>


<script>
        function ocultarMensagem() {
            var message = document.getElementById('message');
            setTimeout(function() {
                message.style.display = 'none';
            }, 3000);
        }

        ocultarMensagem();
    </script>


<?php include('footer.php'); ?>