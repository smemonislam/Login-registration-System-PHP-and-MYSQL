<?php require_once "../templates/header.php"; ?>
<div class="main">
    <h2 class="mb_10">Login</h2>
    <form action="" method="post">
        <table class="t2">
            <tr>
                <td>Email</td>
                <td><input type="text" name="" autocomplete="off"></td>
            </tr>
            <tr>
                <td>Password</td>
                <td><input type="password" name="" autocomplete="off"></td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <input type="submit" value="Login" name="form1">
                    <a href="forget-password.php" class="primary_color">Forget Password</a>
                </td>
            </tr>
        </table>
    </form>
</div>
</div>
<?php require_once "../templates/footer.php"; ?>