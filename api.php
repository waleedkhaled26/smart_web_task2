<?php
header('Content-Type: application/json; charset=utf-8');

$host = 'sql202.infinityfree.com';
$db = 'if0_42403982_web';
$user = 'if0_42403982';
$pass = 'yWP6DxKdrDY';
$charset = 'utf8mb4';

try {
    $pdo = new PDO("mysql:host=$host;charset=$charset", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);

    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$db` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    $pdo->exec("USE `$db`");
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS people (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(150) NOT NULL,
            age INT NOT NULL,
            status TINYINT(1) NOT NULL DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
    ");

    $action = $_GET['action'] ?? 'list';
    $input = json_decode(file_get_contents('php://input'), true) ?: $_POST;

    if ($action === 'list') {
        $stmt = $pdo->query('SELECT id, name, age, status FROM people ORDER BY id DESC');
        echo json_encode(['success' => true, 'people' => $stmt->fetchAll()]);
        exit;
    }

    if ($action === 'add') {
        $name = trim($input['name'] ?? '');
        $age = filter_var($input['age'] ?? null, FILTER_VALIDATE_INT);

        if ($name === '' || $age === false || $age < 1) {
            http_response_code(422);
            echo json_encode(['success' => false, 'message' => 'الاسم والعمر مطلوبان']);
            exit;
        }

        $stmt = $pdo->prepare('INSERT INTO people (name, age, status) VALUES (?, ?, 0)');
        $stmt->execute([$name, $age]);

        echo json_encode(['success' => true]);
        exit;
    }

    if ($action === 'toggle') {
        $id = filter_var($input['id'] ?? null, FILTER_VALIDATE_INT);

        if ($id === false || $id < 1) {
            http_response_code(422);
            echo json_encode(['success' => false, 'message' => 'رقم السجل غير صحيح']);
            exit;
        }

        $stmt = $pdo->prepare('SELECT status FROM people WHERE id = ?');
        $stmt->execute([$id]);
        $person = $stmt->fetch();

        if (!$person) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'السجل غير موجود']);
            exit;
        }

        $status = $person['status'] ? 0 : 1;
        $stmt = $pdo->prepare('UPDATE people SET status = ? WHERE id = ?');
        $stmt->execute([$status, $id]);

        echo json_encode(['success' => true, 'status' => $status]);
        exit;
    }

    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'طلب غير صحيح']);
} catch (PDOException $error) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'تعذر الاتصال بقاعدة البيانات']);
}
