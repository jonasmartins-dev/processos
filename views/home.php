<!DOCTYPE html>
<html lang="pt-br">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://eloamadureira.com.br/css/style.css">
    
    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>

    <title><?php echo $title; ?></title>
</head>

<body>

    <div class="container">

        <div class="jumbotron">

            <div class="row mt-2">

                <div class="col-sm-8 mb-1">
                    <h1 class="display-4">Processos</h1>
                    <p class="lead">Controle de Processos</p>

                    <form action="/processos/cpf" method="post">

                        <div class="form-group">
                            <input type="text" name="cpf" class="form-control w-50" id="recipient-cpf" placeholder="Digite seu CPF" required>
                            <small id="cpf_error" style="color: red"></small>
                        </div>

                        <button type="submit" class="btn btn-primary">Consultar Processo</button>

                    </form>

                </div>

                <div class="col-sm-4  mb-1">

                    <h3>Entrar</h3>

                    <form action="/autenticar" method="post">

                        <div class="form-group">
                            <label for="recipient-email" class="col-form-label">Email:</label>
                            <input type="email" name="email" class="form-control" id="recipient-email" required>
                        </div>
                        <div class="form-group">
                            <label for="recipient-senha" class="col-form-label">Senha:</label>
                            <input type="password" name="senha" class="form-control" id="recipient-senha" required>
                        </div>

                        <button type="submit" class="btn btn-primary">Entrar</button>

                    </form>
                </div>

            </div>

        </div>

    </div>

<script>
    $(document).ready(function () {
        function validarCPF() {
            var strCPF = $("#recipient-cpf").val().replace(/-/g, "").replace(/\./g, "");
            var Soma;
            var Resto;
            Soma = 0;
            if (strCPF == "00000000000") return false;

            for (i = 1; i <= 9; i++)
                Soma = Soma + parseInt(strCPF.substring(i - 1, i)) * (11 - i);

            Resto = (Soma * 10) % 11;
            if ((Resto == 10) || (Resto == 11))
                Resto = 0;

            if (Resto != parseInt(strCPF.substring(9, 10)))
                return false;

            Soma = 0;
            for (i = 1; i <= 10; i++)
                Soma = Soma + parseInt(strCPF.substring(i - 1, i)) * (12 - i);

            Resto = (Soma * 10) % 11;
            if ((Resto == 10) || (Resto == 11))
                Resto = 0;
            if (Resto != parseInt(strCPF.substring(10, 11)))
                return false;

            return true;
        }

        let cpfElement =  $('#recipient-cpf');
        cpfElement.mask('000.000.000-00');

        cpfElement.change(function () {
            let cpf = $(this);
            let errorCpf = $('#cpf_error');

            if(cpf.val().length < 14){
                errorCpf.text('CPF em formato inválido');
                cpf.focus();
                cpf.val('');
            }else{
                if (!validarCPF()) {
                    errorCpf.text('CPF inválido!');
                    cpf.focus();
                    cpf.val('');
                }else{
                    errorCpf.text('');
                }
            }
        });
    })
</script>
