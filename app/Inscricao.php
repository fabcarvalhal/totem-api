<?php 

class Inscricao {
    
    private  $requiredKeys =  array('cpf','telefone','nome','id_evento','email');
    private  $requiredKeysForStudent = array('id_curso','id_faculdade');

    public function inscrever(array $input) {
        $validateInput = $this->validateInput($input);
        if($validateInput->error) {
            echo json_encode($validateInput);
            return;
        }
        $inputAsObject = (object) $input;

        $validateEvent = Eventos::checkIfExists($inputAsObject->id_evento);
        if($validateEvent->error) {
            echo json_encode($validateEvent);
            return;
        }
        $validatePerson = Pessoas::checkIfExists($inputAsObject->cpf);
        if($validatePerson->exists == false) {

            $studentData = new DadosEstudante(null,null,null);
            if(property_exists($inputAsObject, 'matricula') && $inputAsObject->matricula != "") {
                $studentData->matricula = $inputAsObject->matricula;
                $studentData->curso     = $inputAsObject->id_curso;
                $studentData->faculdade = $inputAsObject->id_faculdade;

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
            }
            
            $person = new Pessoa(
                $inputAsObject->nome,
                $studentData,
                $inputAsObject->telefone,
                $inputAsObject->email,
                $inputAsObject->cpf
            );

            $insertPerson = Pessoas::cadastrar($person);
            if($insertPerson->error) {
                echo json_encode($insertPerson);
                return;
            }
        } else {
            $checkHasSubscribed = Eventos::checkPersonHasSubscribed($inputAsObject->cpf, $inputAsObject->id_evento);
            if($checkHasSubscribed->error) {
                echo json_encode($checkHasSubscribed);
                return;
            }
        }

        $response = new StdClass();
        $response->error = false;
        $response->message = "";
        try {
            $conexao = Conexao::getConnection();
            $statement = $conexao->prepare("INSERT INTO eventos_pessoas_inscricoes (id_pessoa,id_evento) VALUES((SELECT id FROM pessoas WHERE cpf = :cpf),:id_evento)");
            $statement->bindValue(":cpf", $inputAsObject->cpf, PDO::PARAM_STR);
            $statement->bindValue(":id_evento", $inputAsObject->id_evento, PDO::PARAM_STR);
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

        $requiredKeys = array();
        
        if(isset($input['matricula']) && $input['matricula'] != '') {
            $requiredKeys = array_merge($this->requiredKeysForStudent, $this->requiredKeys);
        } else {
            
            $requiredKeys = $this->requiredKeys;
        }

        $flipRequiredKeys = array_flip($requiredKeys);
        $missing = array_diff_key($flipRequiredKeys, $input);

        if(sizeof($missing) > 0) {
            $response->error = true;
            $missingKeys = array_map(function($miss){
              return str_replace("id_","",$requiredKeys[$miss]);
            },$missing); 
            
            $response->message = "Os seguintes dados estão faltando: ".implode(",",$missingKeys);
            return $response;
        }


        $invalidValues = [];
        foreach($requiredKeys as $val) {
            if(array_key_exists($val, $input)){
                if(is_null($input[$val])  || $input[$val] == "") {
                    $invalidValues[] = str_replace("id_","",$val);
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