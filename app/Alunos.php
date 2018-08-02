<?php 

class Alunos {

    public function checkIfExists($matricula) {
        $response = new StdClass();
        $response->error = false;
        $response->message = "";

        try {
            $conexao = Conexao::getConnection();
            $statement = $conexao->prepare("SELECT COUNT(1) AS contagem FROM lunos WHERE matricula = :matricula");
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
        $response = new StdClass();
        $response->error = false;
        $response->message = "";
        
        try {
            $conexao = Conexao::getConnection();
            $statement = $conexao->prepare("INSERT INTO alunos(nome,email,telefone,matricula,curso,id_instituição) VALUES(:nome,:email,:telefone,:matricula,:curso,:faculdade)");
            $statement->bind_param(":nome",$aluno->nome,PDO::PARAM_STR);
            $statement->bind_param(":email",$aluno->email,PDO::PARAM_STR);
            $statement->bind_param(":telefone",$aluno->telefone,PDO::PARAM_STR);
            $statement->bind_param(":matricula",$aluno->matricula,PDO::PARAM_STR);
            $statement->bind_param(":curso",$aluno->curso,PDO::PARAM_INT);
            $statement->bind_param(":faculdade",$aluno->faculdade,PDO::PARAM_INT);
            $statement->execute();

            if($statement->rowCount() > 0 ) {
                $response->error = false;
                return $response;
            }
            $response->error = true;
            $response->message = "O aluno não estava cadastrado no sistema, porém ocorreu um erro ao tentar cadastrá-lo.";
            return $response;
        } catch(Exception $e) {
            $response->error = true;
            $response->message = "O aluno não estava cadastrado no sistema, porém ocorreu um erro ao tentar cadastrá-lo.";
            return $response;
        }
        
    }

}

?>