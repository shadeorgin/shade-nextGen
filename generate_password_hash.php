<?php

$passwords = [
    'admin@charity.org' => 'Admin@123',
    'john.doe@email.com' => 'JohnDoe@123',
    'sarah.smith@email.com' => 'SarahSmith@123'
];

echo "Password hashes for sample_data.sql:\n\n";

foreach ($passwords as $email => $password) {
    $hash = password_hash($password, PASSWORD_DEFAULT);
    echo "User: $email\n";
    echo "Password hash: $hash\n\n";
}

echo "Update sample_data.sql users table insert with these hashes.\n";

