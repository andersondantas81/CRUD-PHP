<?php
require_once 'classes/Usuario.php';
require_once 'classes/Funcoes.php';

$objUser = new Usuario();
$objFcs = new Funcoes();

if(isset($_POST['btCadastrar'])){
    if($objUser->queryInsert($_POST) == 'ok'){
        header('location: /CRUD');
    }else{
        echo '<script type="text/javascript">alert("Erro em cadastrar");</script>';
    }
}

if(isset($_POST['btAlterar'])){
    if($objUser->queryUpdate($_POST) == 'ok'){
        header('location: ?acao=edit&func='.$objFcs->base64($_POST['func'],1));
    }else{
        echo '<script type="text/javascript">alert("Erro em alterar");</script>';
    }
}

if(isset($_POST['btTrans'])){
    if($objUser->queryUpdate($_POST) == 'ok'){
        header('location: ?acao=edit&func='.$objFcs->base64($_POST['func'],1));
    }else{
        echo '<script type="text/javascript">alert("Erro em alterar");</script>';
    }
}

if(isset($_GET['acao'])){
    switch($_GET['acao']){
        case 'edit': $func = $objUser->querySeleciona($_GET['func']); break;
        case 'delet':
            if($objUser->queryDelete($_GET['func']) == 'ok'){
                header('location: /CRUD');
            }else{
                echo '<script type="text/javascript">alert("Erro em deletar");</script>';
            }
                break;
    }
}
?>
<!DOCTYPE HTML>
<html lang="pt-br">
<head>
	<meta charset="utf-8">
	<title>Formulário de cadastro</title>
	<link href="css/estilo.css" rel="stylesheet" type="text/css" media="all">
</head>
<body>

<div id="lista">
    <?php foreach($objUser->querySelect() as $rst){ ?>
    <div class="funcionario">
                                       // transforma o login ISO-8859-1 em  UTF-8 para exibir na tela
        <div class="nome"><?=$objFcs->tratarCaracter($rst['login'], 2)?></div>
                                                            //codificando o id
        <div class="mensagem"><a href="?acao=mensagem&func=<?=$objFcs->base64($rst['id'], 1)?>" title="Enviar mensagem"><img src="img/ico-mensagem.png" width="16" height="16" alt="Mensagem"></a></div>
        <div class="transferir"><a href="?acao=trans&func=<?=$objFcs->base64($rst['email'], 1)?>" title="Tranfeir Dinheiro"><img src="img/ico-transfer.png" width="16" height="16" alt="Transfeir"></a></div>
        <div class="editar"><a href="?acao=edit&func=<?=$objFcs->base64($rst['id'], 1)?>" title="Editar dados"><img src="img/ico-editar.png" width="16" height="16" alt="Editar"></a></div>
        <div class="excluir"><a href="?acao=delet&func=<?=$objFcs->base64($rst['id'], 1)?>" title="Excluir esse dado"><img src="img/ico-excluir.png" width="16" height="16" alt="Excluir"></a></div>
    </div>
    <?php } ?>
</div>

<div id="formulario">
    <form name="formCad" action="" method="post">
    	<label>Nome: </label><br>
        <input type="text" name="nome" required="required" value="<?=$objFcs->tratarCaracter((isset($func['nome']))?($func['nome']):(''), 2)?>"><br>
        <label>E-mail: </label><br>
        <input type="mail" name="email" required="required" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$" value="<?=$objFcs->tratarCaracter((isset($func['email']))?($func['email']):(''), 2)?>"><br>
        <?php if(isset($_GET['acao']) <> 'edit' ||  $_GET['acao'] === 'trans'){ ?>
        <label>Senha: </label><br>
        <input type="password" name="senha" required="required"><br>
        <?php } ?>
        <?php if(!isset($_GET['acao']) || $_GET['acao'] <> 'mensagem' ) {?>
        <label>Saldo: </label><br>
        <input type="text" name="saldo" required="required"><br>
        <?php } ?>
        <?php if(isset($_GET['acao']) &&  $_GET['acao'] === 'trans'){ ?>
        <label>Valor da Transferência: </label><br>
        <input type="text" name="valor" required="required"><br>
        <?php } ?>
        <?php if(isset($_GET['acao']) &&  $_GET['acao'] === 'mensagem'){ ?>
        <label>Assunto: </label><br>
        <input type="text" name="assuntor" required="required"><br>
        <label>Mensagem: </label><br>
        <textarea name="mensagem" rows="8" cols="50" required="required"></textarea><br>
        <?php } ?>
        <br>
        
        <? if(isset($_GET['acao'])){ ?>       
        <? switch($_GET['acao']) : case 'edit' : ?>
            <input type="submit" name="btAlterar" value="Alterar">
        <? break; case 'mensagem' : ?>
            <input type="submit" name="btEnviar" value="Enviar">
        <? break; case 'trans' : ?>
            <input type="submit" name="btTrans" value="Transferir" >
        <? break; endswitch; }else{?>
            <input type="submit" name="btCadastrar" value="Cadastrar" >
        <?}?>
        <input type="text" name="func" value="<?=(isset($func['id']))?($objFcs->base64($func['id'], 1)):('')?>">
    </form>
</div>

</body>
</html>