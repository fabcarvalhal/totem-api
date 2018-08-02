<?php 
class Inscricao {
    
    private  $requiredKeys =  array('id_faculdade','id_curso','matricula','telefone','nome','id_evento');

    public function inscrever(array $input) {
        $validateInput = $this->validateInput($input);
        if($validateInput->error) {
            echo json_encode($validateInput);
            return;
        }
        $inputAsObject = (object) $input;
        $validateInstituition = Instituicoes::checkIfExists($inputAsObject->id_faculdade);
        if($validateInstituition->error) {
            echo json_encode($validateInstituition);
            return;
        }
        
        $validateCourse = Cursos::checkIfExists($inputAsObject->id_curso);
        if($validateCourse->error) {
            echo json_encode($validateCourse);
            return;
        }

        $validateEvent = Eventos::checkIfExists($inputAsObject->id_evento);
        if($validateEvent->error) {
            echo json_encode($validateEvent);
            return;
        }

        $getEventName = Eventos::getEventNameById($id);
        if($getEventName->error) {
            echo json_encode($getEventName);
            return;
        }

        $checkHasSubscribed = Eventos::checkStudentHasSubscribed($inputAsObject->matricula, $inputAsObject->id_evento);
        if($checkHasSubscribed->error) {
            echo json_encode($checkHasSubscribed);
            return;
        }

        $validateStudent = Alunos::checkIfExists($inputAsObject->matricula);
        $student = null;
        if($validateStudent->error == true) {
            $student = new AlunoDTO(
                $inputAsObject->nome,
                $inputAsObject->matricula,
                $inputAsObject->telefone,
                $inputAsObject->curso,
                $inputAsObject->faculdade,
                $inputAsObject->email
            );

            $insertStudent = Alunos::cadastrar($student);
            if($insertStudent->error) {
                echo json_encode($insertStudent);
                return;
            }
        }

        $response = new StdClass90;
        $response->error = false;
        $response->message = "";
        try {
            $conexao = Conexao::getConnection();
            $statement = $conexao->prepare("INSERT INTO :tabela (aluno,nome_aluno,checkin,checkout) VALUES(:matricula,:nomealuno,0,0)");
            $statement->bindValue(":tabela", $getEventName, PDO::PARAM_STR);
            $statement->bindValue(":matricula", $student->matricula, PDO::PARAM_INT);
            $statement->bindValue(":nomealuno", $student->nome, PDO::PARAM_STR);
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
    
    
    /*
        Checa se todas as chaves existem e não são nulas no input
    */
    private function validateInput(array $input) {        
        $response = new StdClass();
        $response->error = false;
        $response->message = "";
        
        $flipRequiredKeys = array_flip($this->requiredKeys);
        $missing = array_diff_key($flipRequiredKeys, $input);


        if(sizeof($missing) > 0) {
            $response->error = true;
            $missingKeys = array_map(function($miss){
              return $this->requiredKeys[$miss];
            },$missing); 
            
            $response->message = "Os seguintes dados estão faltando: ".implode(",",$missingKeys);
            return $response;
        }


        $invalidValues = [];
        foreach($this->requiredKeys as $val) {
            if(array_key_exists($val, $input)){
                if(is_null($input[$val])  || $input[$val] == "") {
                    $invalidValues[] = $val;
                }
            }
        }

        if(sizeof($invalidValues) > 0 ) {
            $response->error = true;
            $response->message = "Os seguiintes dados não podem ser nulos ou vazios: ".implode(",", $invalidValues);
            return $response;
        }

        $response->error = false;
        return $response;
    }

}


?>