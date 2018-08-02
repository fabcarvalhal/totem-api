<?php 

class AlunoDTO {
    public $nome;
    public $matricula;
    public $telefone;
    public $curso;
    public $faculdade;
    public $email;

    function __construct($nome,$matricula,$telefone,$curso,$faculdade,$email) {
        $this->nome = $nome;
        $this->matricula = $matricula;
        $this->telefone = $telefone;
        $this->curso = $curso;
        $this->faculdade = $faculdade;
        $this->email = $email;
    }
}

?>