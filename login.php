<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
session_start();

// 1️⃣ التحقق من وصول البيانات
if (!isset($_POST['username']) || !isset($_POST['password'])) {
    header("Location: login.html");
    exit;
}

$username = trim($_POST['username']);
$password = trim($_POST['password']);

// 2️⃣ التحقق من الحقول الفارغة
if ($username == "" || $password == "") {
    echo "<script>
            alert('Please fill in all fields');
            window.location.href='login.html';
          </script>";
    exit;
}

// 3️⃣ الاتصال بقاعدة البيانات
$conn = mysqli_connect("localhost", "root", "", "MarketingShop");

if (!$conn) {
    die("Database connection failed");
}

// 4️⃣ جلب المستخدم من جدول admins
$sql = "SELECT * FROM admins WHERE username = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "s", $username);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// 5️⃣ التحقق من وجود المستخدم
if ($row = mysqli_fetch_assoc($result)) {

    // 6️⃣ التحقق من كلمة المرور
    if (password_verify($password, $row['password'])) {

        // 7️⃣ إنشاء Session
        $_SESSION['admin'] = $row['username'];

        // 8️⃣ تحويل إلى صفحة المنتجات
        header("Location: products.php");
        exit;

    } else {
        echo "<script>
                alert('Wrong password');
                window.location.href='login.html';
              </script>";
        exit;
    }

} else {
    echo "<script>
            alert('Username not found');
            window.location.href='login.html';
          </script>";
    exit;
}
?>

</body>
</html>