<!DOCTYPE html>
<html lang="pt-br">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.4/css/all.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/css/bootstrap-select.min.css">

    <link rel="stylesheet" href="views/css/filter.min.css">

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    
    <title><?php echo $title; ?></title>

</head>

<body>

    <div class="container">

        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">

            <a class="navbar-brand" href="/">Processos</a>

            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">

                <ul class="navbar-nav ml-auto">

                    <?php if (isset($_SESSION['auth'])): ?>

                    <?php if ($_SESSION['perfil'] == 'Gestor'): ?>

                    <li class="nav-item">
                        <a class="nav-link" href="/advogados">Advogados</a>
                    </li>

                    <?php endif ?>

                    <li class="nav-item">
                        <a class="nav-link" href="/clientes">Clientes</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="/processos">Processos</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="/perfil"><?php echo $_SESSION['nome']; ?> (
                            <?php echo $_SESSION['perfil']; ?> )</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="/sair">Sair</a>
                    </li>
                    <!-- 
                    <li class="nav-item active">
                        <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#md-traansferir">
                            Transferir
                        </button>
                    </li>
                    -->
                    <?php else: ?>

                    <li class="nav-item">
                        <a class="nav-link" href="/">Inicio</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="/entrar">Entrar</a>
                    </li>

                    <?php endif ?>

                </ul>

            </div>

        </nav>

    </div>
