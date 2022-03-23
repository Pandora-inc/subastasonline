<?php
$password = "2222";
$password2 = "1234";

$hash = password_hash($password, PASSWORD_DEFAULT);
$hash2 = password_hash($password2, PASSWORD_DEFAULT);

echo "El hash del password '$password' es: " . $hash;
echo '<br>';
echo "El hash del password  2  '$password2' es: " . $hash2;