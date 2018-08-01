<?php 

class Instituicoes {
    public static function getAll() {  
        try{
            $Conexao    = Conexao::getConnection();
            $query      = $Conexao->query("SELECT * FROM Instituição ");
            $instituicoes   = $query->fetchAll(PDO::FETCH_OBJ);
            echo json_encode($instituicoes); 
        }catch(Exception $e){
            echo $e->getMessage();
            exit;
        }
    }
    
}

?>