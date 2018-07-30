<?php 
require 'flight/Flight.php';

Flight::route('/', function(){
    echo 'Nenhuma rota especificada';
});

Flight::route('/eventos', function(){
    include 'app/Eventos.php';
});

Flight::start();


?>