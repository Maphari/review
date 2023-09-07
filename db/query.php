<?php
require_once "./db/connection.php";

try {
    // Fetch all reviews from the database
    $query = $pdo->query("SELECT * FROM rating");
    $reviews = $query->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

function countPeopleInDB($pdo)
{
    // FETCH REVIEW COUNT
    $query_review_count = $pdo->prepare("SELECT COUNT(*) FROM rating");
    $query_review_count->execute();
    $result = $query_review_count->fetchColumn();

    if ($result == 1)
        return $result . " Review";
    else
        return $result . " Reviews";
}

function rate_counter($pdo, $rate)
{
    // FETCH REVIEW COUNT
    $query_review_count = $pdo->prepare("SELECT COUNT(*) FROM rating WHERE user_rating = ?");
    $query_review_count->execute([$rate]);
    $result = $query_review_count->fetchColumn();
    return $result;
}

function calculate_rate_avarage($pdo)
{
    $total_ratings = 0;
    $total_score = 0;

    for ($i = 5; $i >= 1; $i--) {
        $count = rate_counter($pdo, $i);
        $total_ratings += $count;
        $total_score += $count * $i;
    }

    $average_rating = ($total_ratings > 0) ? round($total_score / $total_ratings, 1) : 0;

    return $average_rating;
}
function show_count_percentage($count)
{
    if (($count > 0) && ($count < 10)) {
        return "w-[15%]";
    } elseif (($count > 10) && ($count < 30)) {
        return "w-[25%]";
    } elseif (($count > 40) && ($count < 70)) {
        return "w-[45%]";
    } elseif (($count > 70) && ($count < 80)) {
        return "w-[65%]";
    } elseif (($count > 80) && ($count < 100)) {
        return "w-[100%]";
    }
}