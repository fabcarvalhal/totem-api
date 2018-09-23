<?php 

class DadosEstudante {
    public $matricula;
    public $curso;
    public $faculdade;

    function __construct($matricula, $curso, $faculdade) {
        $this->matricula = $matricula;
        $this->curso = $curso;
        $this->faculdade = $faculdade;
    }
}


?>