<?php
class Eventos {
    public static function getAll() {
        try{

            $Conexao    = Conexao::getConnection();
            $query      = $Conexao->query("SELECT eventos.id,nome,instituicao.nome_faculdade AS faculdade ,data_ini AS inicio, data_fim AS fim FROM eventos INNER JOIN instituicao ON eventos.faculdade = instituicao.id");
            $eventos   = $query->fetchAll(PDO::FETCH_OBJ);
            echo json_encode($eventos);
        
        }catch(Exception $e){
            echo $e->getMessage();
            exit;
        }
    }

    public static function getOngoingEvents() {
        try{

            $Conexao    = Conexao::getConnection();
            $query      = $Conexao->query("SELECT eventos.id,nome FROM eventos WHERE eventos.data_ini <= NOW() AND eventos.data_fim >= NOW()");
            $eventos   = $query->fetchAll(PDO::FETCH_OBJ);
            echo json_encode($eventos);
        
        }catch(Exception $e){
            echo $e->getMessage();
            exit;
        }
    }

    public function getNextEvents() {
        try{
            $Conexao    = Conexao::getConnection();
            $query      = $Conexao->query("SELECT eventos.id,nome,instituicao.nome_faculdade AS faculdade ,data_ini AS inicio, data_fim AS fim FROM eventos INNER JOIN instituicao ON eventos.faculdade = instituicao.id WHERE eventos.data_ini >= NOW()");
            $eventos   = $query->fetchAll(PDO::FETCH_OBJ);
            echo json_encode($eventos);
        
        }catch(Exception $e){
            echo $e->getMessage();
            exit;
        }
    }

    public function cadastrar($input) {
        $response = new StdClass();
        $response->error = false;
        $response->message = "";
        $objecInput = (object) $input;

        $startDateCurrentTimezone = (string) date("Y-m-d H:i:s",strtotime($objecInput->inicio));
        $endDateCurrentTimezone = (string) date("Y-m-d H:i:s",strtotime($objecInput->fim));

        try {
            $conexao = Conexao::getConnection();
            $statement = $conexao->prepare("INSERT INTO eventos (nome,faculdade,data_ini,data_fim,descricao) VALUES (:nome,:faculdade,:data_ini,:data_fim,:descricao)");
            $statement->bindValue(":nome", $objecInput->nome);
            $statement->bindValue(":faculdade", $objecInput->faculdade);
            $statement->bindValue(":data_ini", $startDateCurrentTimezone);
            $statement->bindValue(":data_fim", $endDateCurrentTimezone);
            $statement->bindValue(":descricao", $objecInput->descricao);
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

        $startDateCurrentTimezone = (string) date("Y-m-d H:i:s",strtotime($objecInput->inicio));
        $endDateCurrentTimezone = (string) date("Y-m-d H:i:s",strtotime($objecInput->fim));

        try {
            $conexao = Conexao::getConnection();
            $statement = $conexao->prepare("UPDATE eventos SET nome=:nome,faculdade=:faculdade,data_ini=:data_ini,data_fim=:data_fim,descricao=:descricao WHERE id=:id");
            $statement->bindValue(":nome", $objecInput->nome);
            $statement->bindValue(":faculdade", $objecInput->faculdade);
            $statement->bindValue(":data_ini", $startDateCurrentTimezone);
            $statement->bindValue(":data_fim", $endDateCurrentTimezone);
            $statement->bindValue(":descricao", $objecInput->descricao);
            $statement->bindValue(":id", $objecInput->id);

            if($statement->execute()){
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
            echo json_encode($e);
            return;
        }
    }

    public static function getOne($id) {
        $response = new StdClass();
        $response->error = false;
        $response->message = "";
        try {
            $conexao = Conexao::getConnection();
            $statement = $conexao->prepare("SELECT nome, data_ini AS inicio, data_fim AS fim, faculdade, descricao, nome_faculdade FROM eventos INNER JOIN instituicao ON instituicao.id = eventos.faculdade  WHERE eventos.id = :id");
            $statement->bindValue(":id", $id, PDO::PARAM_INT);
            $statement->execute();
            $result = $statement->fetchAll(PDO::FETCH_OBJ);
            
            if($statement->rowCount() > 0) {
                echo json_encode($result);
                return;
            }

            $response->error = true;
            $response->message = "O evento não existe.";
            echo json_encode($response);
            return;
        } catch(Exception $e) {
            $response->error = true;
            $response->message = "Erro ao obter evento.";
            echo json_encode($response);
            return;
        }
    }

    public static function checkPersonHasSubscribed($cpf,$eventId) {
        $response = new StdClass();
        $response->error = false;
        $response->message = "";
        try {
            $conexao = Conexao::getConnection();
            $statement = $conexao->prepare("SELECT COUNT(1) AS contagem, id_pessoa FROM eventos_pessoas_inscricoes WHERE id_pessoa = (SELECT id FROM pessoas WHERE cpf= :cpf) AND id_evento = :idevento");
            $statement->bindValue(":cpf", $cpf, PDO::PARAM_STR);
            $statement->bindValue(":idevento", $eventId, PDO::PARAM_INT);
            $statement->execute();
            $result = $statement->fetch(PDO::FETCH_OBJ);

            if($result->contagem > 0) {
                $response->error = true;
                $response->message = "Você já se inscreveu nesse evento.";
                return $response;
            }

            $response->message = "Pessao não inscrita neste evento";
            $response->error = false;
            return $response;
        } catch(Exception $e) {
            $response->error = true;
            $response->message = "Erro ao checar se a pessoa já se inscreveu";
            return $response;
        }
    }

    public static function checkIfExists($id) {
        $response = new StdClass();
        $response->error = false;
        $response->message = "";
        try {
            $conexao = Conexao::getConnection();
            $statement = $conexao->prepare("SELECT COUNT(1) AS contagem FROM eventos WHERE id = :id");
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