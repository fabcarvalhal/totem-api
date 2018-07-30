<?php
require_once('Conexao.php');

$bd = new Conexao();
$statement = $bd->prepare("SELECT * FROM Eventos");
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_OBJ);

echo json_encode($result);

?>