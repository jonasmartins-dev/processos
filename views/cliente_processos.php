<div class="container">

    <div class="row mt-2">

        <div class="col-sm-12">

            <?php if (!empty($_SESSION['msg'])): ?>

            <div class="alert alert-warning alert-dismissible fade show" role="alert">
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

            <?php if (!empty($_SESSION['msg'])): ?>

            <div class="alert alert-warning alert-dismissible fade show" role="alert">
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
                    <h2 class="card-title">Processos de: <?= $cliente['nome'] ?></h2>

                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th scope="col">Data</th>
                                <th scope="col">N. Processo</th>
                                <th scope="col">Área</th>
                                <th scope="col">Advogado</th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php foreach ($processos as $processo) : ?>

                            <tr>

                                <td><?php echo date('d/m/Y', strtotime($processo['criacao_dt'])); ?></td>

                                <td>
                                    <a href="/processo/<?php echo $processo['num_proc'] ?>/<?php echo $processo['id_processo'] ?>"> <?php echo $processo['num_proc'] ?> </a>
                                </td>

                                <td><?php echo $processo['area'] ?></td>

                                <td><?php echo $processo['advogado_nome'] ?></td>

                            </tr>

                            <?php endforeach; ?>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>
