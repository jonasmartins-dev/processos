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
                        <h2 class="card-title">Editar perfil</h2>
                    </div>

                    <form action="/perfil/atualizar" method="post" id="form">
                        <div class="form-row">
                            <div class="form-group col-6">
                                <label for="nome">Nome</label>
                                <input type="text" id="nome" class="form-control" value="<?= $perfil->nome?>" readonly>
                            </div>
                            <div class="form-group col-6">
                                <label for="email">E-mail</label>
                                <input type="email" id="email" class="form-control" value="<?= $perfil->email?>" readonly>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-6">
                                <label for="oab">OAB</label>
                                <input type="email" id="oab" class="form-control" value="<?= $perfil->oab?>" readonly>
                            </div>
                            <div class="form-group col-6">
                                <label for="cpf">CPF</label>
                                <input type="email" id="cpf" class="form-control" value="<?= $perfil->cpf?>" readonly>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-6">
                                <label for="senha">Senha de acesso</label>
                                <input type="password" id="senha" class="form-control" name="senha" required>
                                <small id="senha_error" style="color: red"></small>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-12">
                                <div class="d-flex justify-content-end align-items-center">
                                    <button type="submit" class="btn btn-primary">Alterar</button>
                                </div>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {

            let form = $('#form');
            form.submit(function () {
                let senha = $('#senha');
                if(senha.val().length < 4){
                    let senha_error = $('#senha_error');
                    senha_error.text("A senha deve ter no mínimo 4 caracteres");
                    return false;
                }else{
                    senha_error.text("");
                }
            })
        })
    </script>
</div>
