<?php
session_start();

if(isset($_SESSION['username'])) {
    header("location: product.php");
    exit;
}

if(isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if($username === 'admin' && $password === 'password') {
        $_SESSION['username'] = $username;
        
        header("location: product.php");
        exit;
    } else {
        $error = "Tên người dùng hoặc mật khẩu không đúng!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">Đăng nhập</h2>
        <?php if(isset($error)) { ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $error; ?>
            </div>
        <?php } ?>
        <form method="post" action="">
            <div class="mb-3">
                <label for="username" class="form-label">Tên người dùng:</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Mật khẩu:</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary" name="login">Đăng nhập</button>
        </form>
    </div>
</body>
</html>
