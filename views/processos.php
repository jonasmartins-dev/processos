

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
                        <h2 class="card-title"><?php if ($_SESSION['perfil'] != 'Gestor'): ?>Seus<?php endif;?>
                            Processos</h2>
                        <?php if ($_SESSION['perfil'] == 'Gestor'): ?>
                        <h2 class="card-title">
                            <button class="btn btn-primary" data-toggle="modal" data-target="#modal">Cadastrar
                                Processo</button>
                        </h2>
                        <?php endif;?>
                    </div>

                    <table id="processos" class="table table-hover">
                        <thead>
                            <tr>
                                <th scope="col">Data</th>
                                <th scope="col">N. Processo</th>
                                <th scope="col">Área</th>
                                <th scope="col">Advogado</th>
                                <th scope="col">Nome do Cliente</th>
                                <th scope="col">CPF do Cliente</th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php foreach ($processos as $processo) : ?>

                            <tr>

                                <td><?php echo date('d/m/Y', strtotime($processo['criacao_dt'])); ?></td>

                                <td>
                                    <a href="/processo/<?php echo $processo['num_proc'] ."/". $processo['id_processo']?>">
                                        <?php echo $processo['num_proc'] ?> </a>
                                </td>

                                <td><?php echo $processo['area'] ?></td>

                                <td><?php echo $processo['advogado_nome'] ?></td>

                                <td><?php echo $processo['cliente_nome'] ?></td>

                                <td><?php echo $processo['cliente_cpf'] ?></td>

                            </tr>

                            <?php endforeach; ?>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <?php if ($_SESSION['perfil'] == 'Gestor'): ?>
    <div class="modal" tabindex="-1" role="dialog" id="modal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><strong>Cadastrar Processo</strong></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="/processos/salvar" method="post">
                        <div class="form-row">
                            <div class="form-group col-6">
                                <label for="num_proc">Nº do processo</label>
                                <input type="tel" class="form-control" name="num_proc" id="num_proc" placeholder=""
                                    required>
                            </div>
                            <div class="form-group col-6">
                                <label for="area">Área</label>
                                <select class="form-control selectpicker" name="area" id="area" data-live-search="true"
                                    required>
                                    <option value="">Selecione uma área</option>
                                    <?php foreach ($areas as $key => $area) : ?>
                                    <option value="<?= $area?>"><?= $key?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-12">
                                <label for="cliente_id">Cliente</label>
                                <select class="form-control selectpicker" name="cliente_id" id="cliente_id"
                                    data-live-search="true" required>
                                    <option value="">Selecione um cliente</option>
                                    <?php foreach ($clientes as $cliente) : ?>
                                    <option value="<?= $cliente['id_cliente']?>"><?= $cliente['nome']?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-12">
                                <label for="advogado_id">Advogado</label>
                                <select class="form-control selectpicker" name="advogado_id" id="advogado_id"
                                    data-live-search="true" required>
                                    <option value="">Selecione um Advogado</option>
                                    <?php foreach ($advogados as $advogado) : ?>
                                    <option value="<?= $advogado['id_advogado']?>"><?= $advogado['nome']?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-12">
                                <div class="d-flex justify-content-end align-items-center">
                                    <button type="submit" class="btn btn-primary">Salvar Processo</button>
                                    <button type="button" class="btn btn-secondary ml-1" data-dismiss="modal">Fechar</button>
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