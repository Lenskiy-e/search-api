<?php
use models\Article;
use config\bootstrap;

require_once __DIR__ . '/../vendor/autoload.php';


$article = new Article( new bootstrap() );

$response = [];

$m = $_SERVER['REQUEST_METHOD'];

if($_SERVER['REQUEST_METHOD'] === 'GET') {

    if($_GET['id']) {
        header('Content-Type: application/json');
        $response = $article->searchById( $_GET['id'] );
    }

    if($_GET['search_field'] && $_GET['query']) {
        $response = $article->search( $_GET['search_field'], $_GET['query'] );
    }
}

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $response = $article->searchMany( json_decode( file_get_contents('php://input'), true ) );
}

if($response['status'] && $response['status'] === 'error') {
    http_response_code(400);
}
header('Content-Type: application/json');
echo json_encode($response);