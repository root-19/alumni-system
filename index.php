<?php
// Basic test - remove this after debugging
echo "PHP is working! Version: " . phpversion();
echo "<br>Time: " . date('Y-m-d H:i:s');
echo "<br>Extensions: " . (extension_loaded('pdo_mysql') ? 'PDO MySQL YES' : 'PDO MySQL NO');
?>
