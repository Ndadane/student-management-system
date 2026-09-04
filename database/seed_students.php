<?php
/**
 * Seeds the `user` table with realistic student records for scale testing.
 *
 * Usage:
 *   php database/seed_students.php [count]
 *
 * Defaults to 500 records if no count is given. Safe to re-run — usernames
 * are unique per run via a timestamp suffix, so it won't collide with
 * existing data (including the seeded admin account).
 */

require_once __DIR__ . '/../config/database.php';

$count = isset($argv[1]) ? (int) $argv[1] : 500;

$firstNames = [
    'Thabo', 'Lindiwe', 'Sipho', 'Naledi', 'Ayesha', 'Kagiso', 'Zanele', 'Bongani',
    'Fatima', 'Michael', 'Sarah', 'David', 'Emma', 'James', 'Olivia', 'Liam',
    'Noah', 'Sophia', 'Aisha', 'Karabo', 'Thandeka', 'Musa', 'Nomvula', 'Sizwe',
    'Rethabile', 'Amahle', 'Tumelo', 'Precious', 'Andile', 'Nokuthula'
];

$lastNames = [
    'Ndlovu', 'Khumalo', 'Nkosi', 'Dlamini', 'Zulu', 'Mokoena', 'Patel', 'Naidoo',
    'Govender', 'Smith', 'Botha', 'Van der Merwe', 'Pillay', 'Sithole', 'Mahlangu',
    'Mbatha', 'Cele', 'Molefe', 'Radebe', 'Sibanda', 'Chetty', 'Reddy', 'Moyo',
    'Mnguni', 'Buthelezi'
];

$passwordHash = password_hash('Password123!', PASSWORD_DEFAULT);
$runSuffix = time();

$stmt = $data->prepare(
    "INSERT INTO user (username, email, phone, usertype, password) VALUES (?, ?, ?, 'student', ?)"
);

$inserted = 0;
$errors = 0;

for ($i = 1; $i <= $count; $i++) {
    $first = $firstNames[array_rand($firstNames)];
    $last  = $lastNames[array_rand($lastNames)];

    // Unique username per run: firstname.lastname + run suffix + index
    $username = strtolower($first . '.' . $last . '.' . $runSuffix . $i);
    $email    = strtolower($first . '.' . $last . $i) . '@example.com';
    $phone    = '0' . rand(60, 84) . rand(1000000, 9999999);

    $stmt->bind_param('ssss', $username, $email, $phone, $passwordHash);

    try {
        $stmt->execute();
        $inserted++;
    } catch (mysqli_sql_exception $e) {
        $errors++;
        error_log('Seed insert failed for ' . $username . ': ' . $e->getMessage());
    }
}

$stmt->close();

echo "Done. Inserted: $inserted, Errors: $errors, Total requested: $count\n";

$result = $data->query("SELECT COUNT(*) AS total FROM user WHERE usertype = 'student'");
$row = $result->fetch_assoc();
echo "Current total student records in DB: " . $row['total'] . "\n";
