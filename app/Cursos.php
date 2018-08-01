<?php 

class Cursos {
    public function getAll() {
        try{
            
            $Conexao    = Conexao::getConnection();
            $query      = $Conexao->query("SELECT * FROM Cursos ");
            $instituicoes   = $query->fetchAll(PDO::FETCH_OBJ);
            echo json_encode($instituicoes);
            
        }catch(Exception $e){
            echo $e->getMessage();
            exit;
        }
    }
}

?>