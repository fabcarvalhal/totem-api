<?php 


define('DB_HOST'        , "tcp:totem-bd.database.windows.net");
define('DB_USER'        , "Neto");
define('DB_PASSWORD'    , "Almir@lves123");
define('DB_NAME'        , "BD_TOTEM");
define('DB_DRIVER'      , "sqlsrv");
define('DB_PORT'        , "1433");

class Conexao
{
   private static $connection;
  
   private function __construct(){}
  
   public static function getConnection() {
  
       $pdoConfig  = DB_DRIVER . ":". "server = " . DB_HOST . "," . DB_PORT . ";";
       $pdoConfig .= "database=".DB_NAME.";";

       try {
           if(!isset($connection)){
               $connection =  new PDO($pdoConfig, DB_USER, DB_PASSWORD);
               $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
           }
           return $connection;
       } catch (PDOException $e) {
           $mensagem = "Drivers disponiveis: " . implode(",", PDO::getAvailableDrivers());
           $mensagem .= "\nErro: " . $e->getMessage();
           throw new Exception($mensagem);
       }
   }
}




try{

    $Conexao    = Conexao::getConnection();
    $query      = $Conexao->query("SELECT * FROM Eventos ");
    $eventos   = $query->fetchAll(PDO::FETCH_OBJ);
    foreach ($eventos as $ev) {
        echo $ev->nome;
    }

}catch(Exception $e){
    echo $e->getMessage();
    exit;
}

?>