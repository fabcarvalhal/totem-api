<?php
class Eventos {
    public static function getAll() {
        try{

            $Conexao    = Conexao::getConnection();
            $query      = $Conexao->query("SELECT * FROM Eventos ");
            $eventos   = $query->fetchAll(PDO::FETCH_OBJ);
            echo json_encode($eventos);
        
        }catch(Exception $e){
            echo $e->getMessage();
            exit;
        }
    }
}

?>