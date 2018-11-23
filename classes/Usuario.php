<?php
include_once "Conexao.php";
include_once "Funcoes.php";

class Usuario {
    
    private $con;
    private $objfc;
    private $idUsuario;
    private $login;
    private $email;
    private $senha;
    private $saldo;
    private $dataCadastro;
    
    public function __construct(){
        $this->con = new Conexao();
        $this->objfc = new Funcoes();
    }
    
    public function __set($atributo, $valor){
        $this->$atributo = $valor;
    }
    public function __get($atributo){
        return $this->$atributo;
    }
    
    public function querySeleciona($dado){
        try{
            $this->idUsuario = $this->objfc->base64($dado, 2);
            $cst = $this->con->conectar()->prepare("SELECT id, login, email, saldo FROM `usuarios` WHERE `id` = :idUser;");
            $cst->bindParam(":idUser", $this->idUsuario, PDO::PARAM_INT);
            $cst->execute();
            return $cst->fetch();
        } catch (PDOException $ex) {
            return 'error '.$ex->getMessage();
        }
    }
    
    public function querySelect(){
        try{
            $cst = $this->con->conectar()->prepare("SELECT `id`, `login`, `email`, `saldo` FROM `usuarios`;");
            $cst->execute();
            return $cst->fetchAll();
        } catch (PDOException $ex) {
            return 'erro '.$ex->getMessage();
        }
    }
    
    public function queryInsert($dados){
        try{
            $this->login = $this->objfc->tratarCaracter($dados['nome'], 1);
            $this->email = $dados['email'];
            $this->senha = sha1($dados['senha']);
            $this->saldo = $dados['saldo'];
            $cst = $this->con->conectar()->prepare("INSERT INTO `usuarios` (`login`, `password`,  `email`, `saldo`) VALUES (:nome, :senha, :email, :saldo);");
            $cst->bindParam(":nome", $this->login, PDO::PARAM_STR);
            $cst->bindParam(":senha", $this->senha, PDO::PARAM_STR);
            $cst->bindParam(":email", $this->email, PDO::PARAM_STR);
            $cst->bindParam(":saldo", $this->saldo, PDO::PARAM_STR);
            if($cst->execute()){
                return 'ok';
            }else{
                return 'erro';
            }
        } catch (PDOException $ex) {
            return 'error '.$ex->getMessage();
        }
    }
    
    public function queryUpdate($dados){
        try{
            $this->idUsuario = $this->objfc->base64($dados['func'], 2);
            $this->login = $this->objfc->tratarCaracter($dados['nome'], 1);
            $this->email = $dados['email'];
            $cst = $this->con->conectar()->prepare("UPDATE `usuarios` SET  `login` = :nome, `email` = :email WHERE `id` = :idFunc;");
            $cst->bindParam(":idFunc", $this->idUsuario, PDO::PARAM_INT);
            $cst->bindParam(":nome", $this->login, PDO::PARAM_STR);
            $cst->bindParam(":email", $this->email, PDO::PARAM_STR);
            if($cst->execute()){
                return 'ok';
            }else{
                return 'erro';
            }
        } catch (PDOException $ex) {
            return 'error '.$ex->getMessage();
        }
    }
    
    public function queryDelete($dado){
        try{
            $this->idUsuario = $this->objfc->base64($dado, 2);
            $cst = $this->con->conectar()->prepare("DELETE FROM `usuarios` WHERE `id` = :idFunc;");
            $cst->bindParam(":idFunc", $this->idUsuario, PDO::PARAM_INT);
            if($cst->execute()){
                return 'ok';
            }else{
                return 'erro';
            }
        } catch (PDOException $ex) {
            return 'error'.$ex->getMessage();
        }
    }
    
        public function transferir($dados){
        try{
            $this->email = $dados['email'];
            $this->saldo = $dados['saldo'];
            $cst = $this->con->conectar()->prepare("UPDATE `usuarios` SET  `saldo` = :saldo WHERE `email` = :email;");
            $cst->bindParam(":saldo", $this->saldo, PDO::PARAM_INT);
            $cst->bindParam(":email", $this->email, PDO::PARAM_STR);
            if($cst->execute()){
                return 'ok';
            }else{
                return 'erro';
            }
        } catch (PDOException $ex) {
            return 'error '.$ex->getMessage();
        }
        }
    
}
?>