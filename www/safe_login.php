<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Nhập An Toàn - Bảo Vệ SQLi</title>
    <link rel="stylesheet" href="css/common.css">
    <link rel="stylesheet" href="css/safe_login.css">
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar">
        <a href="index.php" class="navbar-brand">🔒 SQLi Defender</a>
        <ul class="navbar-nav">
            <li><a href="vuln_login.php">🔓 Bypass đăng nhập</a></li>
            <li><a href="safe_login.php" class="active">🛡️ Phiên bản an toàn</a></li>
            <li><a href="search.php">🔍 UNION SQLi</a></li>
            <li><a href="blind.php">👁️ Blind SQLi</a></li>
        </ul>
    </nav>

    <div class="container">
        <div class="header">
            <div class="title">
                🛡️ <div class="safe-badge">AN TOÀN</div>
            </div>
            <div class="description">
                Biểu mẫu này được bảo vệ chống tấn công SQL injection
            </div>
        </div>

        <div class="security-info">
            <div class="security-title">🛡️ Tính Năng Bảo Mật:</div>
            <div class="security-code">
                ✅ PDO Prepared Statements<br>
                ✅ Ràng buộc tham số (:u, :p)<br>
                ✅ Lọc dữ liệu đầu vào<br>
                ✅ Tách biệt metadata SQL
            </div>
        </div>

        <?php
        $result = null;
        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $dsn = 'mysql:host=db;dbname=demo;charset=utf8mb4';
                $pdo = new PDO($dsn, 'demo', 'demopass', [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]);

                $u = $_POST['username'] ?? '';
                $p = $_POST['password'] ?? '';

                // ✅ AN TOÀN: Prepared statement với tham số ràng buộc
                $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :u AND password = :p LIMIT 1");
                $stmt->execute([':u' => $u, ':p' => $p]);
                $row = $stmt->fetch();

                if ($row) {
                    $result = "Chào mừng quay lại, " . htmlspecialchars($row['username']) . "! 🎉";
                } else {
                    $error = "Thông tin đăng nhập không hợp lệ";
                }

            } catch (Exception $e) {
                $error = "Có lỗi xảy ra với cơ sở dữ liệu";
            }
        }
        ?>

        <form method="post" class="login-form">
            <div class="form-group">
                <label for="username" class="form-label">👤 Tên đăng nhập</label>
                <input type="text" id="username" name="username" class="form-input"
                       placeholder="Nhập tên đăng nhập..."
                       value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>" required>
            </div>

            <div class="form-group">
                <label for="password" class="form-label">🔑 Mật khẩu</label>
                <input type="password" id="password" name="password" class="form-input"
                       placeholder="Nhập mật khẩu..." required>
            </div>

            <button type="submit" class="btn btn-success">
                <span>🛡️</span>
                <span>Đăng Nhập Bảo Mật</span>
                <span>🔒</span>
            </button>
        </form>

        <?php if ($result): ?>
            <div class="result-box result-success">
                <?php echo $result; ?>
            </div>
        <?php elseif ($error): ?>
            <div class="result-box result-error">
                ❌ <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <div class="protection-note">
            <div class="security-title">💡 Trạng Thái Bảo Vệ:</div>
            <p>Payloads SQLi sẽ được xử lý như chuỗi ký tự, không phải code SQL.</p>
            <p>❌ <code>admin' OR '1'='1</code> sẽ thất bại tại đây!</p>
        </div>

        <div class="nav-links">
            <a href="index.php">🏠 Trang chủ</a>
            <a href="vuln_login.php">🔓 Phiên bản lỗ hổng</a>
            <a href="search.php">🔍 UNION SQLi</a>
            <a href="blind.php">👁️ Blind SQLi</a>
        </div>
    </div>
</body>
</html>
