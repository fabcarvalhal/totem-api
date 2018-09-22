<?php 

// include_once 'DTO/Instituicao';

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

    public function getOne($id){
        $response = new StdClass();
        $response->error = false;
        $response->message = "";

        try {
            $conexao = Conexao::getConnection();
            $statement = $conexao->prepare("SELECT id, nome_faculdade AS nome, endereco FROM instituicao WHERE id=:id");
            $statement->bindValue(":id", $id);
            $statement->execute();
            $result = $statement->fetchAll(PDO::FETCH_OBJ);
            if(count($result) > 0) {
                echo json_encode($result[0]);
                return;
            }
            $response->error = true;
            $response->message = "Instituicao não encontrada.";
            echo json_encode($response);
            return;

        } catch(Exception $e) {
            $response->error = true;
            $response->message = "Erro ao buscar instituicao, tente novamente mais tarde.";
            echo json_encode($response);
            return;
        }
    }

    public function cadastrar($input) {
        $response = new StdClass();
        $response->error = false;
        $response->message = "";
        $objecInput = (object) $input;
        try {
            $conexao = Conexao::getConnection();
            $statement = $conexao->prepare("INSERT INTO instituicao (endereco,nome_faculdade) VALUES (:endereco,:nome_faculdade)");
            $statement->bindValue(":endereco", $objecInput->endereco);
            $statement->bindValue(":nome_faculdade", $objecInput->nome);
            $statement->execute();

            if($statement->rowCount() > 0) {
                $response->message = "Cadastro realizado com sucesso!";
                echo json_encode($response);
                return;
            }
            $response->error = true;
            $response->message = "Não foi possível cadastrar, tente novamente mais tarde.";
            echo json_encode($response);
            return;

        } catch(Exception $e) {
            $response->error = true;
            $response->message = "Erro ao cadastrar, tente novamente mais tarde.";
            echo json_encode($response);
            return;
        }
    }

    public function editar($input) {
        $response = new StdClass();
        $response->error = false;
        $response->message = "";
        $objecInput = (object) $input;
        $checkExists = $this->checkIfExists($objecInput->id);

        if($checkExists->error){
            echo json_encode($checkExists);
            return;
        }

        try {
            $conexao = Conexao::getConnection();
            $statement = $conexao->prepare("UPDATE instituicao SET endereco=:endereco,nome_faculdade=:nome_faculdade WHERE id=:id");
            $statement->bindValue(":endereco", $objecInput->endereco);
            $statement->bindValue(":nome_faculdade", $objecInput->nome);
            $statement->bindValue(":id", $objecInput->id);
            

            if($statement->execute()) {
                $response->message = "Edição realizada com sucesso!";
                echo json_encode($response);
                return;
            }

            $response->error = true;
            $response->message = "Não foi possível editar, tente novamente mais tarde.";
            echo json_encode($response);
            return;

        } catch(Exception $e) {
            $response->error = true;
            $response->message = "Erro ao editar, tente novamente mais tarde.";
            echo json_encode($response);
            return;
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