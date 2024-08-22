<?php
session_start();

$userName = $_SESSION["firstName"] ?? null;
$userId = $_SESSION["userId"] ?? null;


// Use relative path with __DIR__
require_once __DIR__ . "/../util/review/reviews_func.php";

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if (isset($_GET['id']) && !empty($_GET['id'])) {
        $recipeId = (int)$_GET['id'];
        $reviews = get_reviews_for_recipe($recipeId);
        header('Content-Type: application/json');
        echo json_encode($reviews);
    } else {
        header("HTTP/1.1 400 Bad Request");
        header('Content-Type: application/json');
        echo json_encode(["message" => "You must provide recipe id as query parameter (e.g. ?id=1)"]);
    }
} 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_GET['id']) && !empty($_GET['id'])) {
        $recipeId = (int)$_GET['id'];
        $comment = $_POST["comment"] ?? null;
        $rate = $_POST["rate"] ?? null;

        if ($comment && $rate) {
            if (create_review($recipeId, $comment, $rate)) {
                update_averageRating($recipeId);
                $reviews = get_reviews_for_recipe($recipeId);
                header("HTTP/1.1 201 Created");
                header('Content-Type: application/json');
                echo json_encode($reviews);
            } else {
                header("HTTP/1.1 400 Bad Request");
                header('Content-Type: application/json');
                echo json_encode(["status" => "fail", "message" => "You can write only one review per recipe!"]);
            }
        } else {
            header("HTTP/1.1 400 Bad Request");
            header('Content-Type: application/json');
            echo json_encode(["status" => "fail", "message" => "Comment and rate are required!"]);
        }
    } else {
        header("HTTP/1.1 400 Bad Request");
        header('Content-Type: application/json');
        echo json_encode(["message" => "You must provide recipe id as query parameter (e.g. ?id=1)"]);
    }
}
?>