<?php
require_once "./db/connection.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the JSON data from the request body
    $data = file_get_contents("php://input");
    $user = json_decode($data, true);

    if ($user !== null) {
        $username = $user['name'];
        $email = $user['email'];
        $rating = $user['rating'];
        $review = $user['review'];
        $currentTimestamp = time();
        $formattedDate = date("Y-m-d H:i:s", $currentTimestamp);

        try {
            $query = "INSERT INTO rating (user_name, user_email, user_review, user_rating, created)
                       VALUE (:username, :email, :review, :rating, :created)";
            $stmt = $pdo->prepare($query);

            $stmt->bindParam(":username", $username);
            $stmt->bindParam(":email", $email);
            $stmt->bindParam(":review", $review);
            $stmt->bindParam(":rating", $rating);
            $stmt->bindParam(":created", $formattedDate);

            if ($stmt->execute()) {
                $response = [
                    "status" => "success",
                    "message" => "Review received and processed successfully",
                    "data" => $user,
                ];
            } else {
                http_response_code(500); // Internal Server Error
                $response = [
                    "status" => "error",
                    "message" => "Database error: " . $stmt->errorInfo()[2],
                ];
            }
        } catch (PDOException $e) {
            http_response_code(500); // Internal Server Error
            $response = [
                "status" => "error",
                "message" => "Database error: " . $e->getMessage(),
            ];
        }
    } else {
        // Handle JSON decoding error
        http_response_code(400); // Bad Request
        $response = [
            "status" => "error",
            "message" => "Invalid JSON data",
        ];
    }

    // Send JSON response
    echo json_encode($response);
} else {
    http_response_code(405); // Method Not Allowed
    header("Location: ./index.php");
}
?>