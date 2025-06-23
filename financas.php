<?php
session_start();
if (!isset($_SESSION["usuario"]["id"])) {
  http_response_code(403); exit("Usuário não autenticado.");
}
$usuario_id = $_SESSION["usuario"]["id"];
$mysqli = new mysqli('localhost', 'root', '', 'greencash');
if ($mysqli->connect_errno) {
  http_response_code(500); exit("Erro ao conectar ao banco.");
}
header("Content-Type: application/json");

// --- PAGAR DESPESA ---
if (($_GET["action"] ?? '') === "pagar_despesa" && $_SERVER["REQUEST_METHOD"] === "POST") {
    $id = intval($_GET["id"] ?? 0);
    $origem = $_GET["origem"] ?? "total"; // 'banco' ou 'total'
    $stmt = $mysqli->prepare("UPDATE despesas SET pago=1 WHERE id=? AND usuario_id=?");
    $stmt->bind_param("ii", $id, $usuario_id);
    $stmt->execute();
    echo json_encode(["success" => true]);
    exit;
}

// --- REALIZAR PLANO ---
if (($_GET["action"] ?? '') === "realizar_plano" && $_SERVER["REQUEST_METHOD"] === "POST") {
    $id = intval($_GET["id"] ?? 0);
    $origem = $_GET["origem"] ?? "total";
    $stmt = $mysqli->prepare("UPDATE planos SET realizado=1 WHERE id=? AND usuario_id=?");
    $stmt->bind_param("ii", $id, $usuario_id);
    $stmt->execute();
    echo json_encode(["success" => true]);
    exit;
}

// --- INSERIR NOVO ---
if ($_SERVER["REQUEST_METHOD"] === "POST" && empty($_POST["action"])) {
  $tipo = $_POST["tipo"] ?? '';
  $descricao = $_POST["descricao"] ?? '';
  $valor = floatval($_POST["valor"] ?? 0);
  if ($tipo === "receita") {
    $stmt = $mysqli->prepare("INSERT INTO receitas (usuario_id, descricao, valor) VALUES (?, ?, ?)");
    $stmt->bind_param("isd", $usuario_id, $descricao, $valor);
    $stmt->execute();
    echo json_encode(["success" => true, "id" => $stmt->insert_id]);
  } elseif ($tipo === "despesa") {
    $stmt = $mysqli->prepare("INSERT INTO despesas (usuario_id, descricao, valor) VALUES (?, ?, ?)");
    $stmt->bind_param("isd", $usuario_id, $descricao, $valor);
    $stmt->execute();
    echo json_encode(["success" => true, "id" => $stmt->insert_id]);
  } elseif ($tipo === "plano") {
    $prazo = intval($_POST["prazo"] ?? 0);
    $stmt = $mysqli->prepare("INSERT INTO planos (usuario_id, descricao, valor, prazo) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isdi", $usuario_id, $descricao, $valor, $prazo);
    $stmt->execute();
    echo json_encode(["success" => true, "id" => $stmt->insert_id]);
  } else {
    http_response_code(400); echo json_encode(["success" => false, "msg" => "Tipo inválido"]);
  }
  exit;
}

// --- EDITAR ---
if ($_SERVER["REQUEST_METHOD"] === "POST" && ($_POST["action"] ?? '') === "editar") {
  $tipo = $_POST["tipo"] ?? '';
  $id = intval($_POST["id"] ?? 0);
  $descricao = $_POST["descricao"] ?? '';
  $valor = floatval($_POST["valor"] ?? 0);

  if ($tipo === "receita") {
    $stmt = $mysqli->prepare("UPDATE receitas SET descricao=?, valor=? WHERE id=? AND usuario_id=?");
    $stmt->bind_param("sdii", $descricao, $valor, $id, $usuario_id);
    $stmt->execute();
    echo json_encode(["success" => true]);
  } elseif ($tipo === "despesa") {
    $stmt = $mysqli->prepare("UPDATE despesas SET descricao=?, valor=? WHERE id=? AND usuario_id=?");
    $stmt->bind_param("sdii", $descricao, $valor, $id, $usuario_id);
    $stmt->execute();
    echo json_encode(["success" => true]);
  } elseif ($tipo === "plano") {
    $prazo = intval($_POST["prazo"] ?? 0);
    $stmt = $mysqli->prepare("UPDATE planos SET descricao=?, valor=?, prazo=? WHERE id=? AND usuario_id=?");
    $stmt->bind_param("sdiii", $descricao, $valor, $prazo, $id, $usuario_id);
    $stmt->execute();
    echo json_encode(["success" => true]);
  } else {
    http_response_code(400); echo json_encode(["success" => false, "msg" => "Tipo inválido"]);
  }
  exit;
}

// --- EXCLUIR ---
if ($_SERVER["REQUEST_METHOD"] === "POST" && ($_POST["action"] ?? '') === "excluir") {
  $tipo = $_POST["tipo"] ?? '';
  $id = intval($_POST["id"] ?? 0);

  if ($tipo === "receita") {
    $stmt = $mysqli->prepare("DELETE FROM receitas WHERE id=? AND usuario_id=?");
    $stmt->bind_param("ii", $id, $usuario_id);
    $stmt->execute();
    echo json_encode(["success" => true]);
  } elseif ($tipo === "despesa") {
    $stmt = $mysqli->prepare("DELETE FROM despesas WHERE id=? AND usuario_id=?");
    $stmt->bind_param("ii", $id, $usuario_id);
    $stmt->execute();
    echo json_encode(["success" => true]);
  } elseif ($tipo === "plano") {
    $stmt = $mysqli->prepare("DELETE FROM planos WHERE id=? AND usuario_id=?");
    $stmt->bind_param("ii", $id, $usuario_id);
    $stmt->execute();
    echo json_encode(["success" => true]);
  } else {
    http_response_code(400); echo json_encode(["success" => false, "msg" => "Tipo inválido"]);
  }
  exit;
}

// --- GET ITEM INDIVIDUAL (para editar) ---
if ($_SERVER["REQUEST_METHOD"] === "POST" && ($_POST["action"] ?? '') === "get") {
  $tipo = $_POST["tipo"] ?? '';
  $id = intval($_POST["id"] ?? 0);

  if ($tipo === "receita") {
    $stmt = $mysqli->prepare("SELECT id, descricao, valor FROM receitas WHERE id=? AND usuario_id=?");
    $stmt->bind_param("ii", $id, $usuario_id);
    $stmt->execute();
    $res = $stmt->get_result();
    echo json_encode($res->fetch_assoc());
  } elseif ($tipo === "despesa") {
    $stmt = $mysqli->prepare("SELECT id, descricao, valor, pago FROM despesas WHERE id=? AND usuario_id=?");
    $stmt->bind_param("ii", $id, $usuario_id);
    $stmt->execute();
    $res = $stmt->get_result();
    echo json_encode($res->fetch_assoc());
  } elseif ($tipo === "plano") {
    $stmt = $mysqli->prepare("SELECT id, descricao, valor, prazo, realizado FROM planos WHERE id=? AND usuario_id=?");
    $stmt->bind_param("ii", $id, $usuario_id);
    $stmt->execute();
    $res = $stmt->get_result();
    echo json_encode($res->fetch_assoc());
  } else {
    http_response_code(400); echo json_encode(["success" => false, "msg" => "Tipo inválido"]);
  }
  exit;
}

// --- LISTAGEM PADRÃO ---
if ($_SERVER["REQUEST_METHOD"] === "GET") {
  $dados = [];
  $res = $mysqli->query("SELECT id, descricao, valor FROM receitas WHERE usuario_id=$usuario_id ORDER BY data DESC");
  $dados["receitas"] = [];
  while($row = $res->fetch_assoc()) $dados["receitas"][] = $row;
  $res = $mysqli->query("SELECT id, descricao, valor, pago FROM despesas WHERE usuario_id=$usuario_id ORDER BY data DESC");
  $dados["despesas"] = [];
  while($row = $res->fetch_assoc()) $dados["despesas"][] = $row;
  $res = $mysqli->query("SELECT id, descricao, valor, prazo, realizado FROM planos WHERE usuario_id=$usuario_id ORDER BY data DESC");
  $dados["planos"] = [];
  while($row = $res->fetch_assoc()) $dados["planos"][] = $row;
  echo json_encode($dados); exit;
}
?>