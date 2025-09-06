<?php
echo "Debug: Files uploaded successfully!<br>";
echo "Current time: " . date('Y-m-d H:i:s') . "<br>";
echo "PHP Version: " . phpversion() . "<br>";

if (file_exists('bootstrap/app.php')) {
    echo "✓ Laravel detected<br>";
} else {
    echo "✗ Laravel not found<br>";
}

echo "<a href='/admin/attributes'>Go to Attributes</a>";
?>
