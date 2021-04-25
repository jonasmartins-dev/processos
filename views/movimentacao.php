<div class="container">

    <div class="row mt-2">

        <div class="col-sm-12">

            <?php if (!empty($_SESSION['msg'])): ?>

            <div class="alert alert-<?= $_SESSION['msgType'] == 'success'? 'success': 'warning'?> alert-dismissible fade show"
                role="alert">
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
                        <h2 class="card-title">Movimentação do Processo: <?php echo $num_processo; ?>
                        <br>
                        <a href="https://api.whatsapp.com/send?phone=5500000000000&text=Sobre o Processo <?php echo $num_processo; ?>" target="_blank" class="btn btn-primary"><i class="fa fa-whatsapp" aria-hidden="true"></i> Fale Conosco</a>
                        
                        </h2>
                        
                        <h2 class="card-title">
                        <?php if (isset($_SESSION['perfil']) && $_SESSION['perfil'] == 'Advogado'): ?>
                            <button class="btn btn-primary" data-toggle="modal" data-target="#modal">Cadastrar Movimentação</button>
                        <?php endif;?>
                        </h2>

                    </div>

                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th scope="col">Data</th>
                                <th scope="col">Movimentação</th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php foreach ($movimentacoes as $movimentacao) : ?>

                            <tr>

                                <td><?php echo date('d/m/Y', strtotime($movimentacao['criacao_dt'])); ?></td>

                                <td><?php echo $movimentacao['descricao'] ?></td>

                            </tr>

                            <?php endforeach; ?>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <?php if (isset($_SESSION['perfil']) && $_SESSION['perfil'] == 'Advogado'): ?>
    <div class="modal" tabindex="-1" role="dialog" id="modal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><strong>Cadastrar Movimentação</strong></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="/movimentacao/salvar" method="post">
                        <div class="form-group">
                            <label for="descricao">Descrição</label>
                            <input type="text" class="form-control" name="descricao" id="descricao" placeholder=""
                                required>
                            <input hidden type="text" class="form-control" name="id_processo" id="id_processo"
                                placeholder="" value="<?php echo $id_processo; ?>" required>
                            <input hidden type="text" class="form-control" name="processo_num" id="processo_num"
                                placeholder="" value="<?php echo $num_processo; ?>" required>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-12">
                                <div class="d-flex justify-content-end align-items-center">
                                    <button type="submit" class="btn btn-primary">Salvar Movimentação</button>
                                    <button type="button" class="btn btn-secondary ml-1"
                                        data-dismiss="modal">Fechar</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php endif;?>
</div>