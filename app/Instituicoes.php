<?php 

class Instituicoes {
    public static function getAll() {  
        try {
            $Conexao    = Conexao::getConnection();
            $query      = $Conexao->query("SELECT * FROM instituicao ");
            $instituicoes   = $query->fetchAll(PDO::FETCH_OBJ);
            echo json_encode($instituicoes); 
        } catch(Exception $e) {
            echo $e->getMessage();
            exit;
        }
    }
    

    public function checkIfExists($id) {
        $response = new StdClass();
        $response->error = false;
        $response->message = "";
        try {
            $conexao = Conexao::getConnection();
            $statement = $conexao->prepare("SELECT COUNT(1) AS contagem FROM instituicao WHERE id = :id");
            $statement->bindValue(":id", $id, PDO::PARAM_INT);
            $statement->execute();
            $result = $statement->fetch(PDO::FETCH_OBJ);

            if($result->contagem > 0) {
                $response->error = false;
                return $response;
            }

            $response->error = true;
            $response->message = "Instituição não existe";
            return $response;
        } catch(Exception $e) {
            echo json_encode($e);
            $response->error = true;
            $response->message = "Não foi possível checar a instituição.";
            return $response;
        }
        
    }
}

?>