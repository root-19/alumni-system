<?php
// Create SQLite database file for deployment
echo "Creating SQLite database...\n";

$databasePath = __DIR__ . '/database/database.sqlite';

// Create database directory if it doesn't exist
if (!is_dir(dirname($databasePath))) {
    mkdir(dirname($databasePath), 0755, true);
    echo "Created database directory\n";
}

// Create database file if it doesn't exist
if (!file_exists($databasePath)) {
    touch($databasePath);
    echo "Created SQLite database file at: $databasePath\n";
} else {
    echo "SQLite database file already exists at: $databasePath\n";
}

// Set proper permissions
chmod($databasePath, 0644);
chmod(dirname($databasePath), 0755);

echo "Database setup complete!\n";
?>
