<div class="container">

    <div class="row mt-2">
        <div class="col-sm-4 mb-1">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Entrar</h5>

                    <?php if (!empty($_SESSION['msg'])): ?>

                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <?php echo $_SESSION['msg']; ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <?php $_SESSION['msg'] = ''; ?>

                    <?php endif ?>

                    <form action="/autenticar" method="post">

                        <div class="form-group">
                            <label for="recipient-email" class="col-form-label">Email:</label>
                            <input type="text" name="email" class="form-control" id="recipient-email">
                        </div>
                        <div class="form-group">
                            <label for="recipient-senha" class="col-form-label">Senha:</label>
                            <input type="password" name="senha" class="form-control" id="recipient-senha">
                        </div>

                        <button type="submit" class="btn btn-primary">Entrar</button>

                    </form>

                </div>
            </div>
        </div>

    </div>

</div>