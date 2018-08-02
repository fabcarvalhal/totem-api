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
    Checa se todas as chaves existem no input
    Retorna um objeto com error, message
    */
    private function validateInput(array $input) {        
        $response = new StdClass();
        $response->error = null;
        $response->message = "";
        $missing = array_diff_key(array_flip($this->requiredKeys), $input);
        
        if(sizeof($missing) > 0) {
            $response->error = true;
            $missingKeys = array_map(function($miss){
              return $this->requiredKeys[$miss];
            },$missing); 
            
            $response->message = "Os seguintes dados estão faltando: ".implode(",",$missingKeys);
            return $response;
        }
        $response->error = false;
        return $response;
    }

}


?>