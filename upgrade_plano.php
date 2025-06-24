<?php
session_start();
header('Content-Type: application/json');
require 'db.php';
if (!isset($_SESSION["usuario"])) {
  echo json_encode(['sucesso'=>false, 'msg'=>'Não logado']);
  exit;
}
$userId = $_SESSION["usuario"]["id"];
$novo_plano = $_POST['novo_plano'] ?? '';
$senha = $_POST['senha'] ?? '';
$planos_validos = ['intermediario','avancado'];
if (!in_array($novo_plano, $planos_validos)) {
  echo json_encode(['sucesso'=>false, 'msg'=>'Plano inválido']);
  exit;
}
// Confere senha
$senha_hash = md5($senha);
$res = $conn->query("SELECT id FROM usuarios WHERE id=$userId AND senha='$senha_hash'");
if (!$res->num_rows) {
  echo json_encode(['sucesso'=>false, 'msg'=>'Senha incorreta']);
  exit;
}
$conn->query("UPDATE usuarios SET plano='$novo_plano' WHERE id=$userId");
$_SESSION["usuario"]["plano"] = $novo_plano;
echo json_encode(['sucesso'=>true]);