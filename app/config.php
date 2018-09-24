<?php 
date_default_timezone_set("America/Bahia");
include 'DTO/Pessoa.php';
include 'DTO/Curso.php';
include 'DTO/DadosEstudante.php';
include 'DTO/Instituicao.php';

//Registra todas as classes no flight, para que seja possível chamar direto por ele o método necessário
Flight::register('Eventos','Eventos');
Flight::register('Instituicoes','Instituicoes');
Flight::register('Cursos','Cursos');
Flight::register('Inscricao','Inscricao');
Flight::register('PessoaEvento','PessoaEvento');

?>