<?php
$password = '12345';
$hash = password_hash($password, PASSWORD_DEFAULT);
echo "Your new hash for $password is:<br><br>";
echo $hash;
?>