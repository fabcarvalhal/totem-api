<?php 

class Alunos {

    public function checkIfExists($matricula) {
        $response = new StdClass();
        $response->error = false;
        $response->message = "";
        try {
            $conexao = Conexao::getConnection();
            $statement = $conexao->prepare("SELECT COUNT(1) AS contagem FROM Alunos WHERE matricula = :matricula");
            $statement->bind_param(":matricula", $matricula, PDO::PARAM_INT);
            $statement->execute();
            $result = $statement->fetch(PDO::FETCH_OBJ);

            if($result->contagem > 0) {
                $response->error = false;
                return $response;
            }

            $response->error = true;
            $response->message = "Não foi possível checar o aluno.";
            return $response;
        } catch(Exception $e) {
            $response->error = true;
            $response->message = "Não foi possível checar o aluno.";
            return $response;
        }
        
    }

    public function cadastrar(AlunoDTO $aluno) {

    }

}

?>