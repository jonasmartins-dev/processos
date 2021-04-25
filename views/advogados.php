<?php setlocale(LC_ALL, "pt_BR", "pt_BR.iso-8859-1", "pt_BR.utf-8", "portuguese"); ?>

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
                        <h2 class="card-title">Advogados</h2>
                        <h2 class="card-title">
                            <button class="btn btn-primary" data-toggle="modal" data-target="#modal">Cadastrar Advogado</button>
                        </h2>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Nome</th>
                                <th scope="col">CPF</th>
                                <th scope="col">OAB</th>
                                <th scope="col">E-mail</th>
                                <th scope="col">Cadastrado em</th>
                                <th scope="col">Ações</th>
                            </tr>
                            </thead>
                            <tbody>

                            <?php foreach ($advogados as $advogado) : ?>

                                <tr>

                                    <td><?= $advogado['id_advogado'] ?></td>

                                    <td><?= $advogado['nome'] ?></td>

                                    <td><?= $advogado['cpf'] ?></td>

                                    <td><?= $advogado['oab'] ?></td>

                                    <td><?= $advogado['email'] ?></td>

                                    <td><?= date('d/m/Y', strtotime($advogado['criacao_dt'])); ?></td>

                                    <td><a href="/advogados/<?= $advogado['id_advogado'] ?>/editar" title="Editar Advogado"><i class="fas fa-user-edit text-secondary"></i></a></td>
                                </tr>

                            <?php endforeach; ?>

                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="modal" tabindex="-1" role="dialog" id="modal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><strong>Cadastrar Advogado</strong></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="/advogados/salvar" method="post" id="form">
                        <div class="form-group">
                            <label for="nome">Nome</label>
                            <input type="text" class="form-control" name="nome" id="nome" placeholder="" required>
                            <small id="nome_error" style="color: red"></small>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-6">
                                <label for="number">CPF</label>
                                <input type="tel" class="form-control" name="cpf" id="cpf" placeholder="000.000.000-00" required>
                                <small id="cpf_error" style="color: red"></small>
                            </div>
                            <div class="form-group col-6">
                                <label for="oab">OAB</label>
                                <input type="tel" class="form-control" name="oab" id="oab" placeholder="" required>
                                <small id="oab_error" style="color: red"></small>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="email">E-mail</label>
                            <input type="email" class="form-control" name="email" id="email" placeholder="" required>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-6">
                                <label for="senha">Senha de Acesso</label>
                                <input type="password" class="form-control" name="senha" id="senha" placeholder="" required>
                                <small id="senha_error" style="color: red"></small>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-12">
                                <div class="d-flex justify-content-end align-items-center">
                                    <button type="submit" class="btn btn-primary">Salvar Advogado</button>
                                    <button type="button" class="btn btn-secondary ml-1" data-dismiss="modal">Fechar</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
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
    });
</script>
