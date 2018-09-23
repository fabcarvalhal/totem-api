<?php 

include_once 'DTO/Pessoa.php';

class Pessoas {

    public function checkIfExists($cpf) {
        $response = new StdClass();
        $response->error = false;
        $response->message = "";
        try {
            $conexao = Conexao::getConnection();
            $statement = $conexao->prepare("SELECT COUNT(1) AS contagem FROM pessoas WHERE cpf = :cpf");
            $statement->bindValue(":cpf", $cpf, PDO::PARAM_STR);
            $statement->execute();
            $result = $statement->fetch(PDO::FETCH_OBJ);

            if($result->contagem > 0) {
                $response->exists = true;
                return $response;
            }

            $response->exists = false;
            $response->message = "Não foi possível checar a pessoa.";
            return $response;
        } catch(Exception $e) {
            $response->exists = false;
            $response->message = "Não foi possível checar a pessoa.";
            return $response;
        }
        
    }

    public function cadastrar(Pessoa $aluno) {
        $response = new StdClass();
        $response->error = false;
        $response->message = "";
        try {
            $conexao = Conexao::getConnection();
            $statement = $conexao->prepare("INSERT INTO pessoas(nome,email,telefone,matricula,cpf,curso,faculdade) VALUES(:nome,:email,:telefone,:matricula,:cpf,:curso,:faculdade)");
            $statement->bindValue(":nome",$aluno->nome,PDO::PARAM_STR);
            $statement->bindValue(":email",$aluno->email,PDO::PARAM_STR);
            $statement->bindValue(":telefone",$aluno->telefone,PDO::PARAM_STR);
            $statement->bindValue(":matricula",$aluno->dadosEstudante->matricula,PDO::PARAM_STR);
            $statement->bindValue(":cpf", $aluno->cpf, PDO::PARAM_STR);
            $statement->bindValue(":curso",$aluno->dadosEstudante->curso,PDO::PARAM_INT);
            $statement->bindValue(":faculdade",$aluno->dadosEstudante->faculdade,PDO::PARAM_INT);
            $statement->execute();

            if($statement->rowCount() > 0 ) {
                $response->error = false;
                return $response;
            }
            $response->error = true;
            $response->message = "A pessoa não estava cadastrada no sistema, porém ocorreu um erro ao tentar cadastrá-lo.";
            return $response;
        } catch(Exception $e) {
            $response->error = true;
            $response->message = "A pessoa não estava cadastrada no sistema, porém ocorreu um erro ao tentar cadastrá-lo.";
            return $response;
        }
        
    }

}

?>