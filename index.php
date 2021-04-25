<?php

// inicia uma sessao

session_start();

// restrincao de acesso as views

define('ACCESS', false);

// definição das constantes do bando de dados mysql (LOCAL)

/*
define( 'HOST', '127.0.0.1');
define( 'USER', 'usuario' );
define( 'PASSWORD', 'senha' );
define( 'DB_NAME', 'processos' );
define( 'CHARSET', 'utf8' );
*/

// definição das constantes do bando de dados mysql (HOSPEDAGEM)

define( 'HOST', '127.0.0.1');
define( 'USER', 'usuario' );
define( 'PASSWORD', 'senha' );
define( 'DB_NAME', 'processos' );
define( 'CHARSET', 'utf8' );


// inclue a biblioteca do framework flight

require 'flight/Flight.php';

// seta a url da pagina de erro

Flight::map('notFound', function () { 
    include 'views/404.php'; 
});

// registra a classe do bando de dados mysql

Flight::register('db', 'PDO', array('mysql:host=' . HOST . ';dbname=' . DB_NAME.';charset='.CHARSET, USER, PASSWORD, ), function($db) {
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
});



// acesso a pagina inicial

Flight::route('/', function() {

	if (isset($_SESSION['auth'])) {
		Flight::redirect('/processos');
	} else {
        Flight::render('home', array('title' => 'Processos: Inicio'));
        Flight::render('footer');
    }

});

// acesso ao login de advogados

Flight::route('/entrar', function() {

	if (isset($_SESSION['auth'])) {
		Flight::redirect('/processos');
	} else {
        Flight::render('header', array('title' => 'Processos: Entrar'));
        Flight::render('login');
        Flight::render('footer');
    }
    
});

// autenticacao

Flight::route('/autenticar', function() {

	if (isset($_SESSION['auth'])) {
		Flight::redirect('/processos');
	} else {

        $email   = isset($_POST['email']) ? $_POST['email'] : FALSE;
        $senha   = isset($_POST['senha']) ? sha1($_POST['senha']) : FALSE;

        if ($email OR $senha) {

            $db = Flight::db();

            $advogados = $db->query("SELECT * FROM advogados WHERE email='$email' AND senha='$senha'");
    
            if ($advogados->rowCount() < 1) {
                $_SESSION['msg'] = 'Dados de acesso inválido!';
                Flight::redirect('/entrar');
            } else {
    
                $res = $advogados->fetch(PDO::FETCH_ASSOC);
    
                $_SESSION['id']         = $res['id_advogado'];
                $_SESSION['nome']       = $res['nome'];
                $_SESSION['auth']       = $res['email'];
                $_SESSION['perfil']     = $res['perfil'];

                Flight::redirect('/processos');
            } 
            
        } else {
            Flight::redirect('/entrar');
        }

    }

});

// sair do sistema

Flight::route('/sair', function() {
    session_unset();
    session_destroy();
    Flight::redirect('entrar');
});

// processos

Flight::route('/processos', function() {

    if ( isset($_SESSION['auth']) ) {

		$db = Flight::db();

        $id_advogado = $_SESSION['id'];
        
        if ($_SESSION['perfil'] == 'Gestor') {

            $processos = $db->query("SELECT processos.* , advogados.nome AS advogado_nome , clientes.nome AS cliente_nome, clientes.cpf AS cliente_cpf 
                                        FROM processos
                                        INNER JOIN advogados ON processos.advogado_id = advogados.id_advogado
                                        INNER JOIN clientes ON processos.cliente_id = clientes.id_cliente
                                        ORDER BY processos.criacao_dt DESC");

        } else {

            $processos = $db->query("SELECT processos.* , advogados.nome AS advogado_nome , clientes.nome AS cliente_nome, clientes.cpf AS cliente_cpf 
                                        FROM processos
                                        INNER JOIN advogados ON processos.advogado_id = advogados.id_advogado
                                        INNER JOIN clientes ON processos.cliente_id = clientes.id_cliente
                                        WHERE advogados.id_advogado = $id_advogado
                                        ORDER BY processos.criacao_dt DESC");
                                        
        }

        //Clientes
        $sql2 =  "SELECT * FROM clientes";

        $stmt = $db->prepare($sql2);
        $stmt->execute();
        $clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);

        //Advogados
        $perfilAdvogado = 1;

        $sql3 =  "SELECT * FROM advogados WHERE advogados.perfil = {$perfilAdvogado}";

        $stmt = $db->prepare($sql3);
        $stmt->execute();
        $advogados = $stmt->fetchAll(PDO::FETCH_ASSOC);

        //Áreas do processo
        $areas = [
            'Administrativo' => 1,
            'Civil' => 2,
            'Consumidor' => 3,
            'Criminal' => 4,
            'Eleitoral' => 5,
            'Trabalhista' => 6,
            'Previdenciário' => 7
        ];

        Flight::render('header', array('title' => 'Processos: Processos'));
        Flight::render('processos', array('processos' => $processos, 'clientes' => $clientes, 'advogados' => $advogados, 'areas' => $areas));
        Flight::render('footer');

	} else {
		Flight::redirect('/entrar');
	}
    
});

// movimentacao

Flight::route('/processo/@num_proc/@id_processo', function($num_proc, $id_processo){

    $db = Flight::db();

    if (!isset($_SESSION['perfil']) || $_SESSION['perfil'] == "Gestor") {
        $movimentacoes = $db->query("SELECT movimentacoes.* 
                                    FROM movimentacoes
                                    INNER JOIN processos ON movimentacoes.processo_num = processos.id_processo
                                    WHERE processos.num_proc = $num_proc ORDER BY movimentacoes.criacao_dt DESC");
																		

    }else{
        $movimentacoes = $db->query("SELECT movimentacoes.* 
                                    FROM movimentacoes
                                    INNER JOIN processos ON movimentacoes.processo_num = processos.id_processo
                                    WHERE processos.num_proc = $num_proc AND processos.advogado_id = {$_SESSION['id']} ORDER BY movimentacoes.criacao_dt DESC");

}

    Flight::render('header', array('title' => 'Processos: Movimentações'));
    Flight::render('movimentacao' , array('num_processo' => $num_proc, 'id_processo' => $id_processo, 'movimentacoes' => $movimentacoes));
    Flight::render('footer');

});

// cadastrar movimentacao

Flight::route('/movimentacao/salvar', function(){

    if($_SESSION['auth']){
        $db = Flight::db();
        $dados = Flight::request()->data;
				
        $dados->descricao = trim($dados->descricao);
		$dados->id_processo = trim($dados->id_processo);
        $dados->processo_num = trim($dados->processo_num);
 

        
        $sql = "SELECT clientes.telefone FROM clientes
                                        INNER JOIN processos ON clientes.id_cliente = processos.cliente_id
                                         WHERE processos.id_processo = :id_processo";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(":id_processo", $dados->id_processo);
        $stmt->execute();
        $telefone = $stmt->fetch(PDO::FETCH_OBJ);

        $sms = preg_replace("/[^0-9]/", "", $telefone->telefone);

        // key da plataforma comtele.com.br
        $API_KEY = "00000000000000000000000000";

        $sender = $dados->id_processo;

        $content = "Prezado(a) cliente, seu processo foi movimentado. 
                    Acompanhe em nosso site https://site.com.br/processo/".$dados->processo_num."/".$dados->id_processo." 
                    Qualquer dúvida deixe sua mensagem na página e retornaremos o mais breve possível. 
                    Atenciosamente, Nome do escritorio de Advocacia.";

        $receivers = $sms;
        
        $service_url = "https://sms.comtele.com.br/api/v2/send";
        
          $payload = [
              "Sender" => $sender,
              "Content" => $content,
              "Receivers" => $receivers
          ];
        
          $headers = [
              "Content-Type: application/json",
              "Content-Length: ".strlen(json_encode($payload)),
              "auth-key:".$API_KEY
          ];
        
          $curl = curl_init($service_url);
          curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
          curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
          curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
          curl_setopt($curl, CURLOPT_POST, true);
          curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
          curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($payload));
        
          $send = curl_exec($curl);
        
          curl_close($curl);

        $sql = "INSERT INTO movimentacoes ( descricao, processo_num ) VALUES ( :descricao, :id_processo )";
        $stmt = $db->prepare($sql);
				
		$stmt->bindValue(":descricao", $dados->descricao);
		$stmt->bindValue(":id_processo", $dados->id_processo);

        $stmt->execute();

        $_SESSION['msgType'] = 'success';
        $_SESSION['msg'] = 'Movimentação cadastrada com sucesso!';

        Flight::redirect('/processo/'.$dados->processo_num.'/'.$dados->id_processo);


    }else {
        Flight::redirect('/entrar');
    }
});

// cadastrar processo

Flight::route('/processos/salvar', function() {
    if ( isset($_SESSION['auth']) && $_SESSION['perfil'] == "Gestor") {
        $db = Flight::db();

        $dados = Flight::request()->data;

        $dados->num_proc = preg_replace("/[^0-9]/", "", $dados->num_proc);
        $dados->area = trim($dados->area);
        $dados->cliente_id = trim($dados->cliente_id);
        $dados->advogado_id = trim($dados->advogado_id);
				
        //Verificar se o numero do processo já existe
        $sql = "SELECT COUNT(*) AS nprocessos FROM processos WHERE processos.num_proc = {$dados->num_proc}";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $verificarProcessos = $stmt->fetch(PDO::FETCH_OBJ);

        if($verificarProcessos->nprocessos > 0){
            $_SESSION['msgType'] = 'error';
            $_SESSION['msg'] = 'Ja existe um processo cadastrado com o número de processo informado!';
            return Flight::redirect('/processos');
        }

        $sql2 = "INSERT INTO processos ( num_proc, area, advogado_id, cliente_id ) VALUES ( :num_proc, :area, :advogado_id, :cliente_id)";
        $stmt = $db->prepare($sql2);

				$stmt->bindValue(":num_proc", $dados->num_proc);
				$stmt->bindValue(":area", $dados->area);
				$stmt->bindValue(":advogado_id", $dados->advogado_id);
				$stmt->bindValue(":cliente_id", $dados->cliente_id);

        $stmt->execute();

        $_SESSION['msgType'] = 'success';
        $_SESSION['msg'] = 'Processo cadastrado com sucesso!';
        Flight::redirect('/processos');
    } else {
        Flight::redirect('/entrar');
    }
});

// processos por CPF

Flight::route('/processos/cpf', function() {

    $cpf   = isset($_POST['cpf']) ? $_POST['cpf'] : FALSE;

    if ($cpf) {

        $db = Flight::db();

        $sql = "SELECT processos.* , advogados.nome AS advogado_nome, clientes.nome AS cliente_nome
        FROM processos
        LEFT JOIN clientes ON processos.cliente_id = clientes.id_cliente
        LEFT JOIN advogados ON processos.advogado_id = advogados.id_advogado
        WHERE clientes.cpf = :cpf";

        $stmt = $db->prepare($sql);
        $stmt->bindValue(":cpf", $cpf);
        $stmt->execute();
        $processos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        Flight::render('header', array('title' => 'Processos: Processos do Cliente'));
        Flight::render('processos_cpf', array('processos' => $processos));
        Flight::render('footer');
    
    }    

});

// clientes

Flight::route('/clientes', function() {

    if ( isset($_SESSION['auth'])) {
        $db = Flight::db();
        if($_SESSION['perfil'] == "Gestor"){
            $clientes = $db->query("
                    SELECT clientes.*
                    FROM clientes
                    ORDER BY clientes.criacao_dt DESC
                ");
        }else{
            $clientes = $db->query("
                    SELECT 
                            clientes.*
                        FROM processos
                        INNER JOIN clientes ON processos.cliente_id = clientes.id_cliente AND processos.advogado_id = {$_SESSION['id']}
                    GROUP BY clientes.id_cliente
                    ORDER BY clientes.criacao_dt DESC
                ");
        }

        Flight::render('header', array('title' => 'Clientes: Todos os clientes'));
        Flight::render('clientes',  array('clientes' => $clientes));
        Flight::render('footer');
    } else {
        Flight::redirect('/entrar');
    }

});

// Salvar clientes

Flight::route('/clientes/salvar', function() {
    if ( isset($_SESSION['auth']) && $_SESSION['perfil'] == "Gestor") {
        $db = Flight::db();

        $dados = Flight::request()->data;

        $dados->nome = trim($dados->nome);
        $dados->cpf = trim($dados->cpf);
        $dados->telefone = trim($dados->telefone);
        $dados->nascimento_dt = date("Y-m-d", strtotime(str_replace('/','-',$dados->nascimento_dt)));

        //Verificar se ja existe um cliente cadastrado com o cpf informado
        $sql = "SELECT COUNT(*) AS nclientes FROM clientes WHERE clientes.cpf = '{$dados->cpf}'";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $verificarClientes = $stmt->fetch(PDO::FETCH_OBJ);

        if($verificarClientes->nclientes > 0){
            $_SESSION['msgType'] = 'error';
            $_SESSION['msg'] = 'Ja existe um cliente cadastrado com o CPF informado!';
            return Flight::redirect('/clientes');
        }

        $sql2 = "INSERT INTO clientes ( nome, cpf, telefone, nascimento_dt ) VALUES ( :nome, :cpf, :telefone, :nascimento_dt)";
        $stmt = $db->prepare($sql2);

        foreach($dados as $key => $values) {
            $stmt->bindValue(":{$key}", $values);
        }

        $stmt->execute();

        $_SESSION['msgType'] = 'success';
        $_SESSION['msg'] = 'Cliente cadastrado com sucesso!';
        Flight::redirect('/clientes');
    } else {
        Flight::redirect('/entrar');
    }
});

//Processos clientes

Flight::route('/clientes/@idcliente/processos', function($idcliente){
    if ( isset($_SESSION['auth'])) {
        $db = Flight::db();

        if($_SESSION['perfil'] == "Gestor"){

            $sql = "SELECT processos.* , advogados.nome AS advogado_nome , clientes.nome AS cliente_nome, clientes.cpf AS cliente_cpf 
                        FROM processos
                    INNER JOIN advogados    ON processos.advogado_id = advogados.id_advogado
                    INNER JOIN clientes     ON processos.cliente_id = clientes.id_cliente 
                    WHERE cliente_id = :id";

            $sql2 =  "SELECT *
                          FROM clientes
                      WHERE id_cliente = :id";
        }else{
            $sql = "SELECT processos.* , advogados.nome AS advogado_nome , clientes.nome AS cliente_nome, clientes.cpf AS cliente_cpf 
                    FROM processos
                    INNER JOIN advogados    ON processos.advogado_id = advogados.id_advogado
                    INNER JOIN clientes     ON processos.cliente_id = clientes.id_cliente 
                WHERE cliente_id = :id
                AND processos.advogado_id = {$_SESSION['id']}";

            $sql2 = "SELECT 
                            clientes.*
                        FROM processos
                        INNER JOIN clientes ON processos.cliente_id = clientes.id_cliente AND processos.advogado_id = {$_SESSION['id']} AND clientes.id_cliente = :id
                    GROUP BY clientes.id_cliente";
        }

        // query para buscar processos
        $stmt = $db->prepare($sql);
        $stmt->bindValue(":id", $idcliente);
        $stmt->execute();
        $processos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // query para buscar informações do cliente
        $stmt = $db->prepare($sql2);
        $stmt->bindValue(":id", $idcliente);
        $stmt->execute();
        $cliente = $stmt->fetch(PDO::FETCH_ASSOC);

        Flight::render('header', array('title' => 'Clientes: Todos os processos do cliente'));
        Flight::render('cliente_processos',  array('processos' => $processos, 'cliente' => $cliente));
        Flight::render('footer');
    }else{
        Flight::redirect('/entrar');
    }
});


//Atualizar cliente

Flight::route('/clientes/@idcliente/editar', function($idcliente) {

    if ($_SESSION['perfil'] == "Gestor") {

        $db = Flight::db();

        $sql = "SELECT CLT.* FROM clientes AS CLT WHERE CLT.id_cliente = :id";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(":id", $idcliente);
        $stmt->execute();
        $perfil = $stmt->fetch(PDO::FETCH_OBJ);

        Flight::render('header', array('title' => 'Clientes: Editar'));
        Flight::render('clientes_editar',  array('perfil' => $perfil));
        Flight::render('footer');
    } else {
        Flight::redirect('/entrar');
    }

});

Flight::route('/clientes/atualizar', function() {

    if ($_SESSION['perfil'] == "Gestor") {

        $db = Flight::db();
        $dados = Flight::request()->data;

        $dados->id = trim($dados->id);
        $dados->nome = trim($dados->nome);
        $dados->cpf = trim($dados->cpf);
        $dados->telefone = trim($dados->telefone);
        echo $dados->nascimento_dt;
        $dados->nascimento_dt = date("Y-m-d", strtotime(str_replace('/','-',$dados->nascimento_dt)));

        //Verificar se ja existe um advogado cadastrado com o cpf informado

        $sql = "SELECT CLT.id_cliente, CLT.cpf FROM clientes CLT WHERE CLT.cpf = '{$dados->cpf}'";
        $stmt = $db->prepare($sql);
        $stmt->execute();

        $verificarCpfClientes = $stmt->fetch(PDO::FETCH_OBJ);

        if($verificarCpfClientes && $verificarCpfClientes->id_cliente != $dados->id){
            $_SESSION['msgType'] = 'error';
            $_SESSION['msg'] = 'Ja existe um cliente cadastrado com o CPF informado!';
            return Flight::redirect("/clientes/$dados->id/editar");
        }

        $sql3 = "UPDATE clientes SET nome = :nome, cpf = :cpf, telefone = :telefone, nascimento_dt = :nascimento_dt WHERE id_cliente = :id";
        $stmt = $db->prepare($sql3);

        foreach($dados as $key => $values) {
            $stmt->bindValue(":{$key}", $values);
        }

        $stmt->execute();

        $_SESSION['msgType'] = 'success';
        $_SESSION['msg'] = 'Cliente atualizado com sucesso!';
        Flight::redirect("/clientes/$dados->id/editar");

    }else {
        Flight::redirect('/entrar');
    }

});

// advogados

Flight::route('/advogados', function() {

    if ( isset($_SESSION['auth']) && $_SESSION['perfil'] == "Gestor" ) {
        $db = Flight::db();

        $advogados = $db->query("
                    SELECT advogados.*
                    FROM advogados
                    WHERE advogados.perfil = 1
                    ORDER BY advogados.criacao_dt DESC
                ");

        Flight::render('header', array('title' => 'Advogados: Todos os advogados'));
        Flight::render('advogados',  array('advogados' => $advogados));
        Flight::render('footer');
    } else {
        Flight::redirect('/entrar');
    }

});

//Salvar advogados
Flight::route('/advogados/salvar', function() {
    if ( isset($_SESSION['auth']) && $_SESSION['perfil'] == 'Gestor') {
        $db = Flight::db();

        $dados = Flight::request()->data;

        $dados->nome = trim($dados->nome);
        $dados->oab = trim($dados->oab);
        $dados->cpf = trim($dados->cpf);
        $dados->email = trim($dados->email);
        $dados->senha = sha1(trim($dados->senha));
        $dados->perfil = 1;

        //Verificar se ja existe um advogado cadastrado com o cpf informado

        $sql = "SELECT COUNT(*) AS nadvogados FROM advogados WHERE advogados.cpf = '{$dados->cpf}'";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $verificarCpfAdvogados = $stmt->fetch(PDO::FETCH_OBJ);

        if($verificarCpfAdvogados->nadvogados > 0){
            $_SESSION['msgType'] = 'error';
            $_SESSION['msg'] = 'Ja existe um advogado cadastrado com o CPF informado!';
            return Flight::redirect('/advogados');
        }

        //Verificar se ja existe um advogado cadastrado com o e-mail informado

        $sql2 = "SELECT COUNT(*) AS nadvogados FROM advogados WHERE advogados.email = '{$dados->email}'";
        $stmt = $db->prepare($sql2);
        $stmt->execute();
        $verificarEmailAdvogados = $stmt->fetch(PDO::FETCH_OBJ);

        if($verificarEmailAdvogados->nadvogados > 0){
            $_SESSION['msgType'] = 'error';
            $_SESSION['msg'] = 'Ja existe um advogado cadastrado com o e-mail informado!';
            return Flight::redirect('/advogados');
        }

        $sql3 = "INSERT INTO advogados ( nome, oab, cpf, email, senha, perfil) VALUES ( :nome, :oab, :cpf, :email, :senha, :perfil)";

        $stmt = $db->prepare($sql3);
        foreach($dados as $key => $values) {
            $stmt->bindValue(":{$key}", $values);
        }

        $stmt->execute();

        $_SESSION['msgType'] = 'success';
        $_SESSION['msg'] = 'Advogado cadastrado com sucesso!';
        Flight::redirect('/advogados');

    } else {
        Flight::redirect('/entrar');
    }
});

Flight::route('/perfil', function() {

    if ($_SESSION['perfil'] == "Gestor" || $_SESSION['perfil'] == "Advogado") {

        $db = Flight::db();

        $sql = "SELECT ADV.* FROM advogados AS ADV WHERE ADV.id_advogado = :id";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(":id", $_SESSION['id']);
        $stmt->execute();
        $perfil = $stmt->fetch(PDO::FETCH_OBJ);

        Flight::render('header', array('title' => 'Perfil'));
        Flight::render('perfil',  array('perfil' => $perfil));
        Flight::render('footer');
    } else {
        Flight::redirect('/entrar');
    }

});

Flight::route('/perfil/atualizar', function() {

    if ($_SESSION['perfil'] == "Gestor" || $_SESSION['perfil'] == "Advogado") {

        $db = Flight::db();
        $dados = Flight::request()->data;
        $dados->senha = sha1(trim($dados->senha));

        $sql = "UPDATE advogados SET senha = :senha WHERE id_advogado = :id";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(":id", $_SESSION['id']);
        $stmt->bindValue(":senha", $dados->senha);
        $stmt->execute();

        $_SESSION['msgType'] = 'success';
        $_SESSION['msg'] = 'Perfil atualizado com sucesso!';
        Flight::redirect('/perfil' );
    } else {
        Flight::redirect('/entrar');
    }

});

Flight::route('/advogados/@idadvogado/editar', function($idadvogado) {

    if ($_SESSION['perfil'] == "Gestor") {

        $db = Flight::db();

        $sql = "SELECT ADV.* FROM advogados AS ADV WHERE ADV.id_advogado = :id";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(":id", $idadvogado);
        $stmt->execute();
        $perfil = $stmt->fetch(PDO::FETCH_OBJ);

        Flight::render('header', array('title' => 'Advogados: Editar'));
        Flight::render('advogados_editar',  array('perfil' => $perfil));
        Flight::render('footer');
    } else {
        Flight::redirect('/entrar');
    }

});

Flight::route('/advogados/atualizar', function() {

    if ($_SESSION['perfil'] == "Gestor") {

        $db = Flight::db();
        $dados = Flight::request()->data;

        $dados->id = trim($dados->id);
        $dados->nome = trim($dados->nome);
        $dados->oab = trim($dados->oab);
        $dados->cpf = trim($dados->cpf);
        $dados->email = trim($dados->email);

        //Verificar se ja existe um advogado cadastrado com o cpf informado

        $sql = "SELECT advogados.id_advogado, advogados.cpf FROM advogados WHERE advogados.cpf = '{$dados->cpf}'";
        $stmt = $db->prepare($sql);
        $stmt->execute();

        $verificarCpfAdvogados = $stmt->fetch(PDO::FETCH_OBJ);

        if($verificarCpfAdvogados && $verificarCpfAdvogados->id_advogado != $dados->id){
            $_SESSION['msgType'] = 'error';
            $_SESSION['msg'] = 'Ja existe um advogado cadastrado com o CPF informado!';
            return Flight::redirect("/advogados/$dados->id/editar");
        }

        //Verificar se ja existe um advogado cadastrado com o e-mail informado

        $sql2 = "SELECT advogados.id_advogado ,advogados.email FROM advogados WHERE advogados.email = '{$dados->email}'";
        $stmt = $db->prepare($sql2);
        $stmt->execute();

        $verificarEmailAdvogados = $stmt->fetch(PDO::FETCH_OBJ);

        if($verificarEmailAdvogados && $verificarEmailAdvogados->id_advogado != $dados->id){
            $_SESSION['msgType'] = 'error';
            $_SESSION['msg'] = 'Ja existe um advogado cadastrado com o e-mail informado!';
            return Flight::redirect("/advogados/$dados->id/editar");
        }

        if(isset($dados->senha)){
            $dados->senha = sha1(trim($dados->senha));
            $sql3 = "UPDATE advogados SET nome = :nome, email = :email, oab = :oab, cpf = :cpf, senha = :senha WHERE id_advogado = :id";
        }else{
            $sql3 = "UPDATE advogados SET nome = :nome, email = :email, oab = :oab, cpf = :cpf WHERE id_advogado = :id";
        }

        $stmt = $db->prepare($sql3);

        foreach($dados as $key => $values) {
            $stmt->bindValue(":{$key}", $values);
        }

        $stmt->execute();

        $_SESSION['msgType'] = 'success';
        $_SESSION['msg'] = 'Advogado atualizado com sucesso!';
        Flight::redirect("/advogados/$dados->id/editar");

    }else {
        Flight::redirect('/entrar');
    }

});

Flight::start();