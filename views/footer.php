<?php defined('ACCESS') OR exit('Nenhum acesso permitido diretamente no script!'); ?>

<div class="container">

    <div class="row mt-2">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    &copy Processos 2020 | Desenvolvido por <a href="https://newtic.com.br" target="_blank">Newtic</a>
                </div>
            </div>
        </div>
    </div>

</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<script src="https://kit.fontawesome.com/71b2e7e28c.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/js/bootstrap-select.min.js"></script>

<script src="views/js/filter.min.js"></script>

<script>

    $(document).ready(function() {
        let selectLiveSearch= $('.selectpicker').selectpicker();
        if(selectLiveSearch.length){
            selectLiveSearch.selectpicker();
        }
    });

    $('#processos').excelTableFilter();

</script>



</body>

</html>
