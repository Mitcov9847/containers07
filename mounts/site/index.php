<?php
// Подключение к базе данных MySQL
$servername = "database"; // Имя контейнера базы данных
$username = "user";
$password = "secret";
$dbname = "app";

// Создание соединения
$conn = new mysqli($servername, $username, $password, $dbname);

// Проверка соединения
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Проверка на отправку формы
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_message = $_POST["message"];

    // Вставка сообщения в базу данных
    $sql = "INSERT INTO messages (content) VALUES ('$user_message')";

    if ($conn->query($sql) === TRUE) {
        echo "Message saved successfully.";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Закрытие соединения
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Site</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        header {
            background-color: #333;
            color: #fff;
            padding: 10px 0;
            text-align: center;
        }
        main {
            padding: 20px;
        }
        form {
            margin: 20px 0;
        }
        input[type="text"], textarea {
            width: 100%;
            padding: 10px;
            margin: 5px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            padding: 10px 20px;
            background-color: #333;
            color: #fff;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background-color: #555;
        }
    </style>
</head>
<body>

<header>
    <h1>Welcome to the PHP Website</h1>
</header>

<main>
    <h2>Server Information</h2>
    <p><strong>PHP Version:</strong> <?php echo phpversion(); ?></p>
    <p><strong>MySQL Version:</strong> <?php echo mysqli_get_client_version(); ?></p>
    <p><strong>Current Date and Time:</strong> <?php echo date('Y-m-d H:i:s'); ?></p>

    <h2>Submit a Message</h2>
    <form action="" method="POST">
        <label for="message">Your Message:</label>
        <textarea name="message" id="message" rows="4" required></textarea>
        <button type="submit">Submit</button>
    </form>
</main>

</body>
</html>
