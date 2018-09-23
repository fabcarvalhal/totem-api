<?php 

class Pessoa {
    public $nome;
    public $telefone;
    public $dadosEstudante;
    public $email;
    public $cpf;

    function __construct($nome,DadosEstudante $dadosEstudante,$telefone,$email,$cpf) {
        $this->nome = $nome;
        $this->telefone = $telefone;
        $this->dadosEstudante = $dadosEstudante;
        $this->email = $email;
        $this->cpf = $cpf;
    }
}



?>