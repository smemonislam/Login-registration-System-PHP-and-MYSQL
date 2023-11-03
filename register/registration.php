<?php

require_once "../templates/header.php";
require_once "../inc/functions.php";
require_once "../inc/config.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register'])) {
    try {

        $firstName          = inputSanitize($_POST['firstName']);
        $lastName           = inputSanitize($_POST['lastName']);
        $email              = inputSanitize($_POST['email']);
        $phoneNumber        = inputSanitize($_POST['phoneNumber']);
        $password           = inputSanitize($_POST['password']);
        $confirmPassword    = inputSanitize($_POST['confirmPassword']);
        $image              = $_FILES['image'];

        if (empty($firstName) || empty($lastName) || empty($email) || empty($phoneNumber) || empty($password) || empty($confirmPassword) || empty($image)) {
            throw new Exception("All Fields must be required");
        }

        emailValidation($email);
        validatePassword($password, 8);

        if ($password != $confirmPassword) {
            throw new Exception("Password must be match.");
        }

        $name       = $image['name'];
        $tmp_name   = $image['tmp_name'];
        $size       = $image['size'] / 1024;

        $filepath   = pathinfo($name, PATHINFO_EXTENSION);
        $finfo      = finfo_open(FILEINFO_MIME_TYPE);
        $mime       = finfo_file($finfo, $tmp_name);

        if ($mime == "image/jpeg" || $mime == "image/png") {
            if ($size < 1024) {
                $image  = time() . "_{$firstName}" . "." . $filepath;
                move_uploaded_file($tmp_name, "../upload/{$image}");
            } else {
                throw new Exception("Image size is too large.");
            }
        } else {
            throw new Exception("Only support jpeg and png image.");
        }



        $sql = "SELECT * FROM users WHERE email = ?";

        $statement = $conn->prepare($sql);
        $statement->bindValue(':email', $email);
        $total = $statement->rowCount();

        if ($total) {
            throw new Exception("Already eamil exits.");
        }

        $activation_code = bin2hex(random_bytes(32));
        $expiry = "5";
        $dataInsert = 'INSERT INTO users(`firstName`, `lastName`, `email`, `phoneNumber`, `password`, `image`, `activation_code`, `activation_expiry`) VALUES(?,?,?,?,?,?,?,?)';

        $statement = $conn->prepare($dataInsert);
        $statement->bindValue(1, $firstName);
        $statement->bindValue(2, $lastName);
        $statement->bindValue(3, $email);
        $statement->bindValue(4, $phoneNumber);
        $statement->bindValue(5, password_hash($password, PASSWORD_DEFAULT));
        $statement->bindValue(6, $image);
        $statement->bindValue(7, $activation_code);
        $statement->bindValue(8, date('Y-m-d H:i:s',  time() + $expiry));

        $statement->execute();
        $conn = null;
        echo "Successfully.";
    } catch (Exception $e) {
        $errorMessage = $e->getMessage();
    }
}



?>

<div class="main">
    <h2 class="mb_10">Registration</h2>
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" enctype="multipart/form-data">
        <table class="t2">
            <?php
            if (isset($errorMessage)) :
                echo "<p class='error-message'>{$errorMessage}</p>";
            endif;
            ?>
            <tr>
                <td>First Name</td>
                <td><input type="text" name="firstName" autocomplete="off"></td>
            </tr>
            <tr>
                <td>Last Name</td>
                <td><input type="text" name="lastName" autocomplete="off"></td>
            </tr>
            <tr>
                <td>Email</td>
                <td><input type="text" name="email" autocomplete="off"></td>
            </tr>
            <tr>
                <td>Phone</td>
                <td><input type="text" name="phoneNumber" autocomplete="off"></td>
            </tr>
            <tr>
                <td>Password</td>
                <td><input type="password" name="password" autocomplete="off"></td>
            </tr>
            <tr>
                <td>Retype Password</td>
                <td><input type="password" name="confirmPassword" autocomplete="off"></td>
            </tr>
            <tr>
                <td>Image</td>
                <td><input type="file" name="image" autocomplete="off"></td>
            </tr>
            <tr>
                <td></td>
                <td><input type="submit" value="Submit" name="register"></td>
            </tr>
        </table>
    </form>
</div>
</div>

<?php require_once "../templates/footer.php"; ?>