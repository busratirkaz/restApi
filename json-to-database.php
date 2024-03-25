<?php
// Veritabanı bağlantısı için gerekli bilgiler
$servername = "localhost";
$username = "username";
$password = "password";
$dbname = "database";

// Veritabanı bağlantısını oluştur
$conn = new mysqli($servername, $username, $password, $dbname);

// Bağlantıyı kontrol et
if ($conn->connect_error) {
    die("Veritabanı bağlantısı başarısız: " . $conn->connect_error);
}

// JSON verilerini çekmek için curl kullanımı
function getJSONData($url) {
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $json = curl_exec($curl);
    curl_close($curl);
    return json_decode($json, true);
}

// Posts verilerini çek ve veritabanına ekle
$postsData = getJSONData("https://jsonplaceholder.typicode.com/posts");
foreach ($postsData as $post) {
    $userId = $post['userId'];
    $id = $post['id'];
    $title = $post['title'];
    $body = $post['body'];
    $sql = "INSERT INTO posts (user_id, post_id, title, body) VALUES ('$userId', '$id', '$title', '$body')";
    if ($conn->query($sql) === false) {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Comments verilerini çek ve veritabanına ekle
$commentsData = getJSONData("https://jsonplaceholder.typicode.com/comments");
foreach ($commentsData as $comment) {
    $postId = $comment['postId'];
    $id = $comment['id'];
    $name = $comment['name'];
    $email = $comment['email'];
    $body = $comment['body'];
    $sql = "INSERT INTO comments (post_id, comment_id, name, email, body) VALUES ('$postId', '$id', '$name', '$email', '$body')";
    if ($conn->query($sql) === false) {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Veritabanı bağlantısını kapat
$conn->close();
?>
