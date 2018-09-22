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

Flight::route('GET /curso/@id', function($id){
    Flight::Cursos()->getOne($id);
});

Flight::route('GET /evento/@id', function($id){
    Flight::Eventos()->getOne($id);
});

Flight::route('GET /instituicao/@id', function($id){
    Flight::Instituicoes()->getOne($id);
});


Flight::route('POST /inscrever', function(){
    Flight::Inscricao()->inscrever(Flight::request()->data->getData());
});

// ADM Endpoints

Flight::route('POST /adm/instituicao/cadastrar', function() {
    Flight::Instituicoes()->cadastrar(Flight::request()->data->getData());
});

Flight::route('POST /adm/instituicao/editar', function() {
    Flight::Instituicoes()->editar(Flight::request()->data->getData());
});

Flight::route('POST /adm/curso/cadastrar', function() {
    Flight::Cursos()->cadastrar(Flight::request()->data->getData());
});

Flight::route('POST /adm/curso/editar', function() {
    Flight::Cursos()->editar(Flight::request()->data->getData());
});

Flight::route('POST /adm/evento/cadastrar', function() {
    Flight::Eventos()->cadastrar(Flight::request()->data->getData());
});

Flight::route('POST /adm/evento/editar', function() {
    Flight::Eventos()->editar(Flight::request()->data->getData());
});

Flight::start();


?>