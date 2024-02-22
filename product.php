    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>Quản lý sản phẩm</title>
</head>
<body>
    <h1>Quản lý sản phẩm</h1>

    <?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Kiểm tra nếu các trường bắt buộc không được nhập
    $errors = [];
    if (empty($_POST['name'])) {
        $errors['name'] = "Vui lòng nhập tên sản phẩm.";
    }
    if (empty($_POST['price'])) {
        $errors['price'] = "Vui lòng nhập giá sản phẩm.";
    }
    if (empty($_POST['quantity'])) {
        $errors['quantity'] = "Vui lòng nhập số lượng sản phẩm.";
    }

    // Nếu không có lỗi, xử lý dữ liệu
    if (empty($errors)) {
        // Xử lý dữ liệu ở đây
        echo "Dữ liệu đã được xác nhận và xử lý.";
    } else {
        // Hiển thị các thông báo lỗi nếu có
        foreach ($errors as $error) {
            echo '<div class="alert alert-danger" role="alert">' . $error . '</div>';
        }
    }
}
    $conn = mysqli_connect("localhost:3306", "root", "", "product_management");

    if (!$conn) {
        die("Kết nối không thành công: " . mysqli_connect_error());
    }

    function addProduct($conn, $name, $price, $size, $manufacturer, $quantity) {
        $sql = "INSERT INTO products (name, price, size, manufacturer, quantity) VALUES ('$name', '$price', '$size', '$manufacturer', '$quantity')";
        if (mysqli_query($conn, $sql)) {
            echo "<p>Sản phẩm mới đã được thêm vào hệ thống.</p>";
        } else {
            echo "Lỗi: " . $sql . "<br>" . mysqli_error($conn);
        }
    }

    if(isset($_POST['submit'])) {
        $name = $_POST['name'];
        $price = $_POST['price'];
        $size = $_POST['size'];
        $manufacturer = $_POST['manufacturer'];
        $quantity = $_POST['quantity'];

        addProduct($conn, $name, $price, $size, $manufacturer, $quantity);
    }

    function updateProduct($conn, $id, $name, $price, $size, $manufacturer, $quantity) {
    $sql = "UPDATE products SET name='$name', price='$price', size='$size', manufacturer='$manufacturer', quantity='$quantity', updated_at=NOW() WHERE id='$id'";
    if (mysqli_query($conn, $sql)) {
        echo "<p>Sản phẩm đã được cập nhật thành công.</p>";
    } else {
        echo "Lỗi: " . $sql . "<br>" . mysqli_error($conn);
    }
}

if(isset($_POST['update'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    $size = $_POST['size'];
    $manufacturer = $_POST['manufacturer'];
    $quantity = $_POST['quantity'];

    updateProduct($conn, $id, $name, $price, $size, $manufacturer, $quantity);
}

function softDeleteProduct($conn, $id) {
    $sql = "UPDATE products SET deleted_at=NOW() WHERE id='$id'";
    if (mysqli_query($conn, $sql)) {
        echo "<p>Sản phẩm đã được xóa thành công.</p>";
    } else {
        echo "Lỗi: " . $sql . "<br>" . mysqli_error($conn);
    }
}

if(isset($_POST['delete'])) {
    $id = $_POST['id'];

    softDeleteProduct($conn, $id);
}
    ?>
<form method="post" action="" class="needs-validation" novalidate>
    <div class="mb-3">
        <label for="name" class="form-label">Tên sản phẩm:</label>
        <input type="text" class="form-control" id="name" name="name" value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>" required>
        <div class="invalid-feedback">Vui lòng nhập tên sản phẩm.</div>
    </div>
    <div class="mb-3">
        <label for="price" class="form-label">Giá:</label>
        <input type="number" class="form-control" id="price" name="price" value="<?php echo isset($_POST['price']) ? htmlspecialchars($_POST['price']) : ''; ?>" required>
        <div class="invalid-feedback">Vui lòng nhập giá sản phẩm hợp lệ.</div>
    </div>
    <div class="mb-3">
        <label for="size" class="form-label">Kích thước:</label>
        <input type="text" class="form-control" id="size" name="size" value="<?php echo isset($_POST['size']) ? htmlspecialchars($_POST['size']) : ''; ?>">
    </div>
    <div class="mb-3">
        <label for="manufacturer" class="form-label">Nhà sản xuất:</label>
        <input type="text" class="form-control" id="manufacturer" name="manufacturer" value="<?php echo isset($_POST['manufacturer']) ? htmlspecialchars($_POST['manufacturer']) : ''; ?>">
    </div>
    <div class="mb-3">
        <label for="quantity" class="form-label">Số lượng:</label>
        <input type="number" class="form-control" id="quantity" name="quantity" value="<?php echo isset($_POST['quantity']) ? htmlspecialchars($_POST['quantity']) : ''; ?>" required>
        <div class="invalid-feedback">Vui lòng nhập số lượng sản phẩm hợp lệ.</div>
    </div>
    <button type="submit" class="btn btn-primary" name="submit">Thêm sản phẩm</button>
</form>

</div>
    <h2>Danh sách sản phẩm</h2>
    <table class="table">
       
        <thead>
    <tr>
      <th scope="col">ID</th>
            <th scope="col">Tên sản phẩm</th>
            <th scope="col">Giá</th>
            <th scope="col">Kích thước</th>
            <th scope="col">Nhà sản xuất</th>
            <th scope="col">Số lượng</th>
            <th scope="col">Ngày thêm</th>
            <th scope="col">Ngày chỉnh sửa</th>
            <th scope="col">Hành động</th>
    </tr>
  </thead>
        <?php
        $sql = "SELECT * FROM products WHERE deleted_at IS NULL";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            while($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>".$row['id']."</td>";
                echo "<td>".$row['name']."</td>";
                echo "<td>".$row['price']."</td>";
                echo "<td>".$row['size']."</td>";
                echo "<td>".$row['manufacturer']."</td>";
                echo "<td>".$row['quantity']."</td>";
                echo "<td>".$row['created_at']."</td>";
                echo "<td>".$row['updated_at']."</td>";
                echo "<td>";
                echo "<form action='' method='post'>";
                echo "<input type='hidden' name='id' value='".$row['id']."'>";
                echo "<input type='submit' name='delete' value='Xóa'>";
                echo "<input type='submit' name='update' value='Cập nhật'>";
                echo "</form>";
                echo "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='9'>Không có sản phẩm nào.</td></tr>";
        }
        ?>
    </table>

    <?php
    mysqli_close($conn);
    ?>
</body>
</html>
