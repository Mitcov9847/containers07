<?php
// Определяем текущую страницу
$page = isset($_GET['page']) ? $_GET['page'] : 'home';
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Простой сайт на PHP</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Добро пожаловать на наш сайт!</h1>
        <nav>
            <ul>
                <li><a href="?page=home">Главная</a></li>
                <li><a href="?page=about">О нас</a></li>
                <li><a href="?page=contact">Контакты</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <?php
        // Контент для разных страниц
        if ($page === 'home') {
            echo "<h2>Главная страница</h2>";
            echo "<p>Это главная страница нашего сайта.</p>";
        } elseif ($page === 'about') {
            echo "<h2>О нас</h2>";
            echo "<p>Мы — команда разработчиков, создающая веб-сайты с использованием PHP и Docker.</p>";
        } elseif ($page === 'contact') {
            echo "<h2>Контакты</h2>";
            echo "<p>Вы можете связаться с нами через email: info@company.com</p>";
        } else {
            echo "<h2>Страница не найдена</h2>";
            echo "<p>Извините, такой страницы не существует.</p>";
        }
        ?>
    </main>

    <footer>
        <p>&copy; 2025 Простой сайт на PHP. Все права защищены.</p>
    </footer>
</body>
</html>
