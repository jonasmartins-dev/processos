<div class="container">

    <div class="row mt-2">

        <div class="col-sm-12">

            <?php if (!empty($_SESSION['msg'])): ?>

                <div class="alert alert-<?= $_SESSION['msgType'] == 'success'? 'success': 'warning'?> alert-dismissible fade show" role="alert">
                    <?php echo $_SESSION['msg']; ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <?php $_SESSION['msg'] = ''; ?>

            <?php endif ?>

        </div>

    </div>

    <div class="row mt-2">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">
                        <h2 class="card-title">Editar Advogado</h2>
                    </div>

                    <form action="/advogados/atualizar" method="post" id="form">
                        <div class="form-row">
                            <div class="form-group col-12">
                                <input hidden type="tel" id="id" name="id" class="form-control" value="<?= $perfil->id_advogado?>" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-6">
                                <label for="nome">Nome</label>
                                <input type="text" id="nome" name="nome" class="form-control" value="<?= $perfil->nome?>" required>
                            </div>
                            <div class="form-group col-6">
                                <label for="email">E-mail</label>
                                <input type="email" id="email" name="email" class="form-control" value="<?= $perfil->email?>" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-6">
                                <label for="oab">OAB</label>
                                <input type="tel" id="oab" name="oab" class="form-control" value="<?= $perfil->oab?>" required>
                            </div>
                            <div class="form-group col-6">
                                <label for="cpf">CPF</label>
                                <input type="tel" id="cpf" name="cpf" class="form-control" value="<?= $perfil->cpf?>" required>
                                <small id="cpf_error" style="color: red"></small>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-6">
                                <label for="senha">Senha de acesso</label>
                                <input type="password" name="senha" id="senha" class="form-control" name="senha">
                                <small id="senha_error" style="color: red"></small>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-12">
                                <div class="d-flex justify-content-end align-items-center">
                                    <button type="submit" class="btn btn-primary" id="submit">Alterar</button>
                                </div>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            function validarCPF() {
                var strCPF = $("#cpf").val().replace(/-/g, "").replace(/\./g, "");
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

            let cpfElement =  $('#cpf');
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



            let senhaElement =  $('#senha');
            senhaElement.change(function () {

                let senha =  $('#senha');
                let senha_error = $('#senha_error');

                if(senha.val().length < 4){
                    senha.val('');
                    senha_error.text("A senha deve ter no mínimo 4 caracteres");
                    return false;
                }else{
                    senha_error.text("");
                }
            });

            let form = $('#form');

            form.submit(function () {
                $('#submit').prop('disabled',true );
            })
        });
    </script>
</div>
