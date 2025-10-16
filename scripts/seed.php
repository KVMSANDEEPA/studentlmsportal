<?php
require_once __DIR__ . '/../includes/init.php';
require_once __DIR__ . '/../config/auth.php';

$pdo = get_db();

// Create tables
$sql = file_get_contents(__DIR__ . '/../db/schema.sql');
$pdo->exec($sql);

echo "Schema created.\n";

// Ensure admin user exists
$adminEmail = getenv('ADMIN_EMAIL') ?: 'admin@example.com';
$adminPass = getenv('ADMIN_PASSWORD') ?: 'admin123';
$adminName = getenv('ADMIN_NAME') ?: 'Admin';

$stmt = $pdo->prepare('SELECT id FROM users WHERE email = ?');
$stmt->execute([$adminEmail]);
if (!$stmt->fetch()) {
    $hash = password_hash($adminPass, PASSWORD_BCRYPT);
    $pdo->prepare('INSERT INTO users (name, email, password_hash, role) VALUES (?, ?, ?, "admin")')->execute([$adminName, $adminEmail, $hash]);
    echo "Admin created: $adminEmail / $adminPass\n";
} else {
    echo "Admin already exists: $adminEmail\n";
}

// Seed courses if none
$count = (int)$pdo->query('SELECT COUNT(*) AS c FROM courses')->fetch()['c'];
if ($count === 0) {
    $courses = [
        ['PHP for Beginners', 'Start coding in PHP with hands-on examples.', 49.00],
        ['MySQL Essentials', 'Learn relational databases and SQL queries.', 39.00],
        ['Tailwind CSS Crash Course', 'Build beautiful UIs quickly with Tailwind.', 29.00],
    ];
    $stmt = $pdo->prepare('INSERT INTO courses (title, description, price) VALUES (?, ?, ?)');
    foreach ($courses as $c) { $stmt->execute($c); }
    echo "Seeded sample courses.\n";
} else {
    echo "Courses already seeded.\n";
}

echo "Done.\n";
