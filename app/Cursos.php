<?php 

class Cursos {
    public function getAll() {
        try{
            
            $Conexao    = Conexao::getConnection();
            $query      = $Conexao->query("SELECT cursos.id,nome,area, instituicao.nome_faculdade  FROM cursos INNER JOIN instituicao WHERE cursos.faculdade = instituicao.id ");
            $instituicoes   = $query->fetchAll(PDO::FETCH_OBJ);
            echo json_encode($instituicoes);
            
        }catch(Exception $e){
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
            $statement = $conexao->prepare("SELECT * FROM cursos WHERE id=:id");
            $statement->bindValue(":id", $id);
            $statement->execute();
            $result = $statement->fetchAll(PDO::FETCH_OBJ);
            if(count($result) > 0) {
                echo json_encode($result[0]);
                return;
            }
            $response->error = true;
            $response->message = "Curso não encontrado.";
            echo json_encode($response);
            return;

        } catch(Exception $e) {
            $response->error = true;
            $response->message = "Erro ao buscar curso, tente novamente mais tarde.";
            echo json_encode($response);
            return;
        }
    }

    public function cadastrar($input){
        $response = new StdClass();
        $response->error = false;
        $response->message = "";
        $objecInput = (object) $input;
        try {
            $conexao = Conexao::getConnection();
            $statement = $conexao->prepare("INSERT into cursos (area,nome,faculdade) VALUES(:area,:nome,:faculdade)");
            $statement->bindValue(":area", $objecInput->area);
            $statement->bindValue(":nome", $objecInput->nome);
            $statement->bindValue(":faculdade", $objecInput->faculdade);
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

    public function editar($input){
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
            $statement = $conexao->prepare("UPDATE cursos SET area=:area,nome=:nome,faculdade=:faculdade WHERE id=:id");
            $statement->bindValue(":area", $objecInput->area);
            $statement->bindValue(":nome", $objecInput->nome);
            $statement->bindValue(":faculdade", $objecInput->faculdade);
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

    public static function checkIfExists($id) {
        $response = new StdClass();
        $response->error = false;
        $response->message = "";
        try {
            $conexao = Conexao::getConnection();
            $statement = $conexao->prepare("SELECT COUNT(1) AS contagem FROM cursos WHERE id = :id");
            $statement->bindValue(":id", $id, PDO::PARAM_INT);
            $statement->execute();
            $result = $statement->fetch(PDO::FETCH_OBJ);

            if($result->contagem > 0) {
                $response->error = false;
                return $response;
            }

            $response->error = true;
            $response->message = "Curso não existe.";
            return $response;
        } catch(Exception $e) {
            $response->error = true;
            $response->message = "Não foi possível checar o curso.";
            return $response;
        }
        
    }

}

?>