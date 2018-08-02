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

    public static function getEventNameById($id) {
        $response = new StdClass();
        $response->error = false;
        $response->message = "";
        $response->eventName= null;
        try {
            $conexao = Conexao::getConnection();
            $statement = $conexao->prepare("SELECT nome FROM Eventos WHERE id = :id LIMIT 1");
            $statement->bind_param(":id", $id, PDO::PARAM_INT);
            $statement->execute();
            $result = $statement->fetch(PDO::FETCH_OBJ);

            if($statement->rowCount() > 0) {
                $response->error = false;
                $response->eventName = $result->nome;
                return $response;
            }

            $response->error = true;
            $response->message = "O evento não existe.";
            return $response;
        } catch(Exception $e) {
            $response->error = true;
            $response->message = "Erro ao checar evento.";
            return $response;
        }
    }

    public function checkIfExists($id) {
        $response = new StdClass();
        $response->error = false;
        $response->message = "";
        try {
            $conexao = Conexao::getConnection();
            $statement = $conexao->prepare("SELECT COUNT(1) AS contagem FROM Eventos WHERE id = :id");
            $statement->bind_param(":id", $id, PDO::PARAM_INT);
            $statement->execute();
            $result = $statement->fetch(PDO::FETCH_OBJ);

            if($result->contagem > 0) {
                $response->error = false;
                return $response;
            }

            $response->error = true;
            $response->message = "Não foi possível checar o evento.";
            return $response;
        } catch(Exception $e) {
            $response->error = true;
            $response->message = "Não foi possível checar o evento.";
            return $response;
        }
        
    }
}

?>