<?php 

//tcp:totem-bd.database.windows.net
define('DB_HOST'        , "DESKTOP-JASFL06\SQLEXPRESS");
define('DB_USER'        , "sa");
define('DB_PASSWORD'    , "123");
define('DB_NAME'        , "totem");
define('DB_DRIVER'      , "sqlsrv");
// define('DB_PORT'        , "1433"); //descomentar para conectar ao banco no azure

class Conexao {
    private static $connection;
    
    private function __construct(){}
        
        public static function getConnection() {
            
            $pdoConfig  = DB_DRIVER . ":". "server = " . DB_HOST;
            if (defined("DB_PORT")){
                $pdoConfig .="," . DB_PORT;
            }
            $pdoConfig .= ";";
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
    ?>