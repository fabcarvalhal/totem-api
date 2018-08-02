<?php 
class Inscricao {
    
    private  $requiredKeys =  array('id_faculdade','id_curso','matricula','telefone','nome','id_evento');

    public function inscrever(array $input) {
        $validateInput = $this->validateInput($input);
        if($validateInput->error) {
            echo json_encode($validateInput);
            return;
        }

        
        //TODO: executar inscrição
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