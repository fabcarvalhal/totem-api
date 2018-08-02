<?php
class Eventos {
    public static function getAll() {
        try{

            $Conexao    = Conexao::getConnection();
            $query      = $Conexao->query("SELECT id,nome FROM Eventos WHERE data >= getutcdate()");
            $eventos   = $query->fetchAll(PDO::FETCH_OBJ);
            echo json_encode($eventos);
        
        }catch(Exception $e){
            echo $e->getMessage();
            exit;
        }
    }

    public static function checkStudentHasSubscribed($studentId,$eventName) {
        $response = new StdClass();
        $response->error = false;
        $response->message = "";
        try {
            $conexao = Conexao::getConnection();
            $query = sprintf("SELECT COUNT(1) AS contagem FROM %s WHERE aluno = :id", $eventName);
            $statement = $conexao->prepare($query);
            $statement->bindValue(":id", $studentId, PDO::PARAM_INT);
            $statement->execute();
            $result = $statement->fetch(PDO::FETCH_OBJ);

            if($result->contagem > 0) {
                $response->error = true;
                $response->message = "Você já se inscreveu nesse evento.";
                return $response;
            }

            $response->error = false;
            return $response;
        } catch(Exception $e) {
            echo $e->getMessage();
            $response->error = true;
            $response->message = "Erro ao checar se o aluno já se inscreveu";
            return $response;
        }
    }

    public static function getEventNameById($id) {
        $response = new StdClass();
        $response->error = false;
        $response->message = "";
        $response->eventName= null;
        try {
            $conexao = Conexao::getConnection();
            $statement = $conexao->prepare("SELECT nome FROM eventos WHERE id = :id order by id desc offset 0 rows fetch next 1 rows only");
            $statement->bindValue(":id", $id, PDO::PARAM_INT);
            $statement->execute();
            $result = $statement->fetchAll(PDO::FETCH_OBJ);
            
            if($statement->rowCount() > 0) {
                $response->error = false;
                $response->eventName = $result[0]->nome;
                return $response;
            }

            $response->error = true;
            $response->message = "O evento não existe.";
            return $response;
        } catch(Exception $e) {
            echo $e->getMessage();
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
            $statement->bindValue(":id", $id, PDO::PARAM_INT);
            $statement->execute();
            $result = $statement->fetch(PDO::FETCH_OBJ);

            if($result->contagem > 0) {
                $response->error = false;
                return $response;
            }

            $response->error = true;
            $response->message = "O evento não existe.";
            return $response;
        } catch(Exception $e) {
            $response->error = true;
            $response->message = "Não foi possível checar o evento.";
            return $response;
        }
        
    }
}

?>