<?php

class Method 
{
  private string $method;

  public function __construct(string $method) {
    $this->method = strtoupper($method);
    $this->validateMethod();
  }

  private function validateMethod(): void {
    $validMethods = ['GET', 'POST', 'PUT', 'DELETE', 'PATCH', 'OPTIONS', 'HEAD'];
    if (!in_array($this->method, $validMethods)) {
      http_response_code(405);
      echo json_encode([
        'error' => 'Bad Request',
        'message' => 'Invalid HTTP method.'
      ]);
      exit;
    }
  }

  public function isPost(): bool {
    return $this->method === 'POST';
  }

  public function isGet(): bool {
    return $this->method === 'GET';
  }

  public function isPut(): bool {
    return $this->method === 'PUT';
  }

  public function isDelete(): bool {
    return $this->method === 'DELETE';
  }

  public function isPatch(): bool {
    return $this->method === 'PATCH';
  }

  public function methodNotAllowed(array $allowedMethods): void {
    http_response_code(405);
    $allowedMethodsStr = implode(', ', $allowedMethods);
    header("Allow: $allowedMethodsStr");

    echo json_encode([
      'error' => 'Method Not Allowed',
      'message' => "This endpoint only accepts " . $allowedMethodsStr . " requests."
    ]);
  }
}

