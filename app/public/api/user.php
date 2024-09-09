<?php

require_once(dirname(__DIR__).'/../config.php');
require_once(dirname(__DIR__).'/../models/Method.php');
require_once(dirname(__DIR__).'/../models/Auth.php');

$method = new Method($_SERVER['REQUEST_METHOD']);

if (!$method->isPost()) {
  $method->methodNotAllowed(['POST']);
  exit;
}

$contentType = $_SERVER['CONTENT_TYPE'] ?? '';

$name = $email = $password = $passwordConfirmation = $birthdate = null;

if (stripos($contentType, 'application/json') !== false) {
  $json = file_get_contents('php://input');
  $data = json_decode($json, true);

  if (json_last_error() !== JSON_ERROR_NONE) {
    http_response_code(400);
    echo json_encode([
      'error' => 'Bad Request',
      'message' => 'Invalid JSON format.'
    ]);
    
    exit;
  }

  $name = filter_var($data['name'] ?? null, FILTER_SANITIZE_SPECIAL_CHARS);
  $email = filter_var($data['email'] ?? null, FILTER_VALIDATE_EMAIL);
  $password = $data['password'] ?? null;
  $passwordConfirmation = $data['password_confirmation'] ?? null;
  $birthdate = $data['birthdate'] ?? null;
} else if (stripos($contentType, 'multipart/form-data') !== false || stripos($contentType, 'application/x-www-form-urlencoded') !== false) {
  $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS);
  $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
  $password = filter_input(INPUT_POST, 'password');
  $passwordConfirmation = filter_input(INPUT_POST, 'password_confirmation');
  $birthdate = filter_input(INPUT_POST, 'birthdate');
} else {
  http_response_code(400);
  echo json_encode([
    'error' => 'Bad Request',
    'message' => 'Unsupported Content-Type. Supported types: application/json, multipart/form-data, application/x-www-form-urlencoded.'
  ]);
  
  exit;
}

if (!$name || !$email || !$password || !$passwordConfirmation || !$birthdate) {
  http_response_code(400);
  echo json_encode([
    'error' => 'Bad Request',
    'error_message' => 'Preencha todos os campos corretamente.',
    'required_fields' => ['name', 'email', 'password', 'password_confirmation', 'birthdate']
  ]);

  exit;
}

$birthdate = explode('/', $birthdate);
if (count($birthdate) !== 3) {
  http_response_code(400);
  echo json_encode([
    'error' => 'Bad Request',
    'error_message' => 'Data de nascimento inválida.'
  ]);

  exit;
}

$birthdate = $birthdate[2] . '-' . $birthdate[1] . '-' . $birthdate[0];
if (strtotime($birthdate) === false || strtotime($birthdate) >= (new DateTime())->modify('-13 years')->getTimestamp()) {
  http_response_code(400);
  echo json_encode([
    'error' => 'Bad Request',
    'error_message' => 'Você precisa ter pelo menos 13 anos para se inscrever.'
  ]);

  exit;
}

if (strlen($password) < 8 || $password !== $passwordConfirmation) {
  http_response_code(400);
  echo json_encode([
    'error' => 'Bad Request',
    'error_message' => $password !== $passwordConfirmation
      ? 'As senhas não coincidem.'
      : 'Sua senha precisa conter pelo menos 8 caracteres.'
  ]);

  exit;
}

if ($auth->emailExists($email)) {
  http_response_code(400);
  echo json_encode([
    'error' => 'Bad Request',
    'error_message' => 'E-mail já cadastrado.'
  ]);

  exit;
}

$auth->registerUser($name, $email, $password, $birthdate);

http_response_code(201);
echo json_encode([
  'message' => 'Cadastro realizado com sucesso!'
]);

exit;
