
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results</title>
    <link rel="stylesheet" href="styles/main.css">
</head>
<body>
    <h1>Search Results</h1>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo '<div class="message">Please enter a valid email address.</div>';
        } else {
            $conn = mysqli_connect("db", "db", "db", "taus_data");

            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            }

            $stmt = mysqli_prepare($conn, "SELECT firstName, lastName, email FROM tbl_student WHERE email = ?");

            mysqli_stmt_bind_param($stmt, "s", $email);
            mysqli_stmt_execute($stmt);

            $result = mysqli_stmt_get_result($stmt);

            if (mysqli_num_rows($result) > 0) {
                $student = mysqli_fetch_assoc($result);

                echo '<div class="message">';
                echo "Student Found: " .
                     htmlspecialchars($student["firstName"]) . " " .
                     htmlspecialchars($student["lastName"]) .
                     "<br>Email: " .
                     htmlspecialchars($student["email"]);
                echo '</div>';
            } else {
                echo '<div class="message">The email address was not found.</div>';
            }

            mysqli_stmt_close($stmt);
            mysqli_close($conn);
        }
    } else {
        echo '<div class="message">Please submit the search form.</div>';
    }
    ?>

    <p><a href="index.php">Return to Search</a></p>
</body>
</html>
