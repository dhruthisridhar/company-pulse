<?php
// Run once in the browser: creates the database and the four tables.
require_once 'includes/functions.php';
header('Content-Type: text/plain; charset=utf-8');
try {
    $pdo = db(false);
    $pdo->exec('CREATE DATABASE IF NOT EXISTS `' . DB_NAME . '` CHARACTER SET utf8mb4');
    echo "Database " . DB_NAME . " ready\n";
    $t = [
     'members' => 'user VARCHAR(16) NOT NULL PRIMARY KEY, pass VARCHAR(255) NOT NULL, created TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
     'profiles' => 'user VARCHAR(16) NOT NULL PRIMARY KEY, about VARCHAR(4096) NOT NULL DEFAULT "",
        FOREIGN KEY (user) REFERENCES members(user) ON DELETE CASCADE',
     'friends' => 'id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, user VARCHAR(16) NOT NULL, friend VARCHAR(16) NOT NULL,
        UNIQUE KEY uf (user, friend),
        FOREIGN KEY (user) REFERENCES members(user) ON DELETE CASCADE,
        FOREIGN KEY (friend) REFERENCES members(user) ON DELETE CASCADE',
     'messages' => 'id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, auth VARCHAR(16) NOT NULL, recip VARCHAR(16) NULL,
        pm TINYINT(1) NOT NULL DEFAULT 0, sent TIMESTAMP DEFAULT CURRENT_TIMESTAMP, message VARCHAR(4096) NOT NULL,
        KEY (auth), KEY (recip),
        FOREIGN KEY (auth) REFERENCES members(user) ON DELETE CASCADE,
        FOREIGN KEY (recip) REFERENCES members(user) ON DELETE CASCADE',
    ];
    foreach ($t as $name => $cols) {
        db()->exec("CREATE TABLE IF NOT EXISTS `$name` ($cols) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
        echo "Table $name ready\n";
    }
    if (!is_dir('uploads/avatars')) mkdir('uploads/avatars', 0755, true);
    echo "uploads/avatars ready\n\nSetup complete. Go to index.php\n";
} catch (PDOException $ex) {
    echo 'Setup failed: ' . $ex->getMessage() . "\nCheck the DB_* settings in includes/functions.php\n";
}
