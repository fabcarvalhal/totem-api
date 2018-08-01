<?php 

require_once('app/flight/Flight.php');
require_once('app/config.php');


Flight::route('/', function(){
    echo 'Nenhuma rota especificada';
});

Flight::route('GET /eventos', function(){
    Flight::Eventos()->getAll();  
});

Flight::route('GET /instituicoes', function(){
    Flight::Instituicoes()->getAll();  
});


Flight::route('GET /cursos', function(){
    Flight::Cursos()->getAll();
});


Flight::route('POST /inscrever', function(){
    Flight::Inscricao()->inscrever(Flight::request()->data->getData());
});

Flight::start();


?>