<?php 
require 'flight/Flight.php';

Flight::route('/', function(){
    echo 'Nenhuma rota especificada';
});

Flight::route('GET /eventos', function(){
    include 'app/Eventos.php';
});

Flight::rout('POST /inscricao', function(){
    include 'app/Inscricao.php';
});

Flight::start();


?>