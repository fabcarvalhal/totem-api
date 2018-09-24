<?php

class PessoaEvento {

    public function checkin($input) {
        $inputAsObject = (object) $input;
        $response = new StdClass();
        $response->error = false;
        $response->message = "";
        $checkHasSubscribed = Eventos::checkPersonHasSubscribed($inputAsObject->cpf,$inputAsObject->id_evento);
        
        if($checkHasSubscribed->error == false) {
            echo json_encode($checkHasSubscribed);
            return;
        }

        $checkAlreadyCheckedin = $this->checkIsCheckedin($inputAsObject->cpf,$inputAsObject->id_evento);
        if($checkAlreadyCheckedin->error) {
            $response->error = true;
            $response->message = "Erro ao verificar se o usuário já efetuou chekin.";
            echo json_encode($response);
            return;
        } else if($checkAlreadyCheckedin->hasCheckedin) {
            $response->error = true;
            $response->message = "Você já fez checkin.";
            echo json_encode($response);
            return;
        }

        $isCheckinPossible = $this->isCheckinPossible($inputAsObject->id_evento);
        if($isCheckinPossible->error) {
            $response->error = true;
            $response->message = "Não foi possível verificar se há possibilidade de chekin neste evento";
            echo json_encode($response);
            return;
        } else if(!$isCheckinPossible->isPossible){
            $response->error = true;
            $response->message = "Não é possível fazer checkin neste evento. Talvez o evento ainda não tenha começa ou já terminou.";
            echo json_encode($response);
            return;
        }

        try {
            $conexao = Conexao::getConnection();
            $statement = $conexao->prepare("UPDATE eventos_pessoas_inscricoes SET data_checkin=NOW() WHERE id_pessoa = (SELECT id FROM pessoas WHERE cpf= :cpf) AND id_evento = :idevento");
            $statement->bindValue(":cpf", $inputAsObject->cpf, PDO::PARAM_STR);
            $statement->bindValue(":idevento", $inputAsObject->id_evento, PDO::PARAM_INT);
            

            if($statement->execute()) {
                $response->error = false;
                $response->message = "Checkin efetuado com sucesso.";
                echo json_encode($response);
                return;
            }

            $response->error = true;
            $response->message = "Não foi possível efetuar checkin, tente novamente mais tarde.";
            echo json_encode($response);
            return;
        } catch(Exception $e) {
            $response->error = true;
            $response->message = "Erro ao efetuar checkin";
            echo json_encode($response);
        }
    }

    private function checkIsCheckedin($cpf,$eventId) {
        $response = new StdClass();
        $response->error = false;
        $response->hasCheckedin = false;
        try {
            $conexao = Conexao::getConnection();
            $statement = $conexao->prepare("SELECT COUNT(1) as contagem FROM eventos_pessoas_inscricoes WHERE data_checkin IS NOT NULL AND id_pessoa = (SELECT id FROM pessoas WHERE cpf= :cpf) AND id_evento = :idevento");
            $statement->bindValue(":cpf", $cpf, PDO::PARAM_STR);
            $statement->bindValue(":idevento", $eventId, PDO::PARAM_INT);
            $statement->execute();
            $resultado = $statement->fetch(PDO::FETCH_OBJ);

            if($resultado->contagem > 0) {
                $response->hasCheckedin = true;
                return $response;
            }

            $response->error = false;
            $response->hasCheckedin = false;
            return $response;
        } catch (Exception $e) {
            $response->error = true;
            return $response;
        }
    }

    private function checkIsCheckedout($cpf,$eventId) {
        $response = new StdClass();
        $response->error = false;
        $response->hasCheckedout = false;
        try {
            $conexao = Conexao::getConnection();
            $statement = $conexao->prepare("SELECT COUNT(1) as contagem FROM eventos_pessoas_inscricoes WHERE data_checkout IS NOT NULL AND id_pessoa = (SELECT id FROM pessoas WHERE cpf= :cpf) AND id_evento = :idevento");
            $statement->bindValue(":cpf", $cpf, PDO::PARAM_STR);
            $statement->bindValue(":idevento", $eventId, PDO::PARAM_INT);
            $statement->execute();
            $resultado = $statement->fetch(PDO::FETCH_OBJ);

            if($resultado->contagem > 0) {
                $response->hasCheckedout = true;
                return $response;
            }

            $response->error = false;
            $response->hasCheckedout = false;
            return $response;
        } catch (Exception $e) {
            $response->error = true;
            return $response;
        }
    }

    public function checkout($input) {
        $inputAsObject = (object) $input;
        $response = new StdClass();
        $response->error = false;
        $response->message = "";
        $checkHasSubscribed = Eventos::checkPersonHasSubscribed($inputAsObject->cpf,$inputAsObject->id_evento);
        
        if($checkHasSubscribed->error == false) {
            echo json_encode($checkHasSubscribed);
            return;
        }

        $checkIsCheckedin = $this->checkIsCheckedin($inputAsObject->cpf,$inputAsObject->id_evento);
        if($checkIsCheckedin->error) {
            $response->error = true;
            $response->message = "Erro ao verificar se o usuário já efetuou chekin.";
            echo json_encode($response);
            return;
        } else if(!$checkIsCheckedin->hasCheckedin) {
            $response->error = true;
            $response->message = "Você não efetuou checkin neste evento, impossivel realizar checkout.";
            echo json_encode($response);
            return;
        }

        $isCheckoutPossible = $this->isCheckoutPossible($inputAsObject->id_evento);
        if($isCheckoutPossible->error) {
            $response->error = true;
            $response->message = "Não foi possível verificar se há possibilidade de checkout neste evento.";
            echo json_encode($response);
            return;
        } else if(!$isCheckoutPossible->isPossible){
            $response->error = true;
            $response->message = "Ainda não é possível fazer checkout.";
            echo json_encode($response);
            return;
        }

        $alreadyCheckedOut = $this->checkIsCheckedout($inputAsObject->cpf,$inputAsObject->id_evento);
        if($alreadyCheckedOut->error) {
            $response->error = true;
            $response->message = "Não foi possível verificar se já efetuou checkout neste evento.";
            echo json_encode($response);
            return;
        } else if($alreadyCheckedOut->hasCheckedout){
            $response->error = true;
            $response->message = "Você já efetuou checkout.";
            echo json_encode($response);
            return;
        }

        try {
            $conexao = Conexao::getConnection();
            $statement = $conexao->prepare("UPDATE eventos_pessoas_inscricoes SET data_checkout=NOW() WHERE id_pessoa = (SELECT id FROM pessoas WHERE cpf= :cpf) AND id_evento = :idevento");
            $statement->bindValue(":cpf", $inputAsObject->cpf, PDO::PARAM_STR);
            $statement->bindValue(":idevento", $inputAsObject->id_evento, PDO::PARAM_INT);
            

            if($statement->execute()) {
                $response->error = false;
                $response->message = "Checkout efetuado com sucesso.";
                echo json_encode($response);
                return;
            }

            $response->error = true;
            $response->message = "Não foi possível efetuar checkout, tente novamente mais tarde.";
            echo json_encode($response);
            return;
        } catch(Exception $e) {
            $response->error = true;
            $response->message = "Erro ao efetuar checkout";
            echo json_encode($response);
        }
    }


    // Checa se é possivel fazer checkin (evento já começou e ainda não terminou)
    private function isCheckinPossible($eventId) {
        $response = new StdClass();
        $response->error = false;
        $response->isPossible = false;
        try {
            $conexao = Conexao::getConnection();
            $statement = $conexao->prepare("SELECT (data_ini <= NOW() AND data_fim > NOW()) as isPossible FROM eventos WHERE id = :idevento");
            $statement->bindValue(":idevento", $eventId, PDO::PARAM_INT);
            $statement->execute();
            $resultado = $statement->fetch(PDO::FETCH_OBJ);

            if($resultado->isPossible) {
                $response->isPossible = true;
                return $response;
            }

            return $response;
        } catch (Exception $e) {
            $response->error = true;
            return $response;
        }
    }

    private function isCheckoutPossible($eventId) {
        $response = new StdClass();
        $response->error = false;
        $response->isPossible = false;
        try {
            $conexao = Conexao::getConnection();
            $statement = $conexao->prepare("SELECT (data_ini < NOW()) as isPossible FROM eventos WHERE id = :idevento");
            $statement->bindValue(":idevento", $eventId, PDO::PARAM_INT);
            $statement->execute();
            $resultado = $statement->fetch(PDO::FETCH_OBJ);

            if($resultado->isPossible) {
                $response->isPossible = true;
                return $response;
            }

            return $response;
        } catch (Exception $e) {
            $response->error = true;
            return $response;
        }
    }
}

?>