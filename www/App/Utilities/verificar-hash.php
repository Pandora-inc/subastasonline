<?php
$password = "2222";
$password2 = "1234";

$hash = '$2y$10$.W.dQvOtark35Vrns9K7IuiK6qJWXI3YBuBJFM2D4rw6TiU8VgGrC'; // profesor
$hash2 = '$2y$10$hoNXNAKbIjrpJN4tFotobeQw2I4gUp19eP.HQamf7RbO5d5Stk7em'; // manu

if (password_verify($password, $hash)) {
    echo "Sip, el password '$password' coincide con este hash.";
} else {
    echo "Nope, el password es incorrecto.";
}

echo '<br>';

if (password_verify($password2, $hash2)) {
    echo "Sip, el password 2  '$password2' coincide con este hash 2.";
} else {
    echo "Nope, el password 2 es incorrecto.";
}