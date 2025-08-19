<?php
header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER['REQUEST_URI'] === "/convert-csv" && $_SERVER['REQUEST_METHOD'] === "POST") {
    if (!isset($_FILES['file']) || $_FILES['file']['error']) {
        http_response_code(400); echo json_encode(["error"=>"Nenhum arquivo válido enviado."]); exit;
    }

    if (pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION) !== 'csv') {
        http_response_code(400); echo json_encode(["error"=>"Envie apenas CSV."]); exit;
    }

    $lines = file($_FILES['file']['tmp_name'], FILE_IGNORE_NEW_LINES);
    $header = str_getcsv(array_shift($lines));
    $data = [];
    foreach ($lines as $line) {
        $data[] = array_combine($header, str_getcsv($line));
    }
    echo json_encode($lines, JSON_PRETTY_PRINT);
    exit;
}

http_response_code(404); 
echo json_encode(["error"=>"Rota não encontrada"]);