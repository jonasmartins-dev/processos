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
                        <h2 class="card-title">Editar cliente</h2>
                    </div>

                    <form action="/clientes/atualizar" method="post">
                        <div class="form-group">
                            <input hidden type="tel" class="form-control" name="id" id="nome" value="<?= $perfil->id_cliente?>" required>
                        </div>
                        <div class="form-group">
                            <label for="nome">Nome</label>
                            <input type="text" class="form-control" name="nome" id="nome" value="<?= $perfil->nome?>" required>
                            <small id="nome_error" style="color: red"></small>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-6">
                                <label for="number">CPF</label>
                                <input type="tel" class="form-control" name="cpf" id="cpf" value="<?= $perfil->cpf?>" placeholder="000.000.000-00" required>
                                <small id="cpf_error" style="color: red"></small>
                            </div>
                            <div class="form-group col-6">
                                <label for="telefone">Telefone</label>
                                <input type="tel" class="form-control" name="telefone" id="telefone" value="<?= $perfil->telefone?>" placeholder="(99) 91234-5678" required>
                                <small id="telefone_error" style="color: red"></small>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-6">
                                <label for="data-nascimento">Data de nascimento</label>
                                <input type="text" class="form-control" name="nascimento_dt" id="nascimento_dt" value="<?= date("d/m/Y", strtotime($perfil->nascimento_dt)) ?>" placeholder="01/01/2020" required>
                                <small id="data_error" style="color: red"></small>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-12">
                                <div class="d-flex justify-content-end align-items-center">
                                    <button type="submit" class="btn btn-primary" id="submit">Atualizar informações</button>
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

            let telefoneElement = $('#telefone');
            telefoneElement.mask('(00) 00000-0000');

            let dataElement = $('#nascimento_dt');
            dataElement.mask('00/00/0000');

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

            telefoneElement.change(function () {
                let telefone = $(this);
                let errorTelefone = $('#telefone_error');

                if(telefone.val().length < 14){
                    errorTelefone.text('Telefone em formato inválido');
                    telefone.focus();
                    telefone.val('');
                }else{
                    errorTelefone.text('');
                }
            });

            dataElement.change(function () {
                let data = $(this);
                let errorData = $('#data_error');

                if(data.val().length < 10){
                    errorData.text('Data em formato inválido');
                    data.focus();
                    data.val('');
                }else{
                    errorData.text('');
                }
            })

            let form = $('#form');
            form.submit(function () {
                $('#submit').prop("disabled", true);
            })
        });
    </script>
</div>
