<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Nhập Lỗ Hổng - Demo SQLi</title>
    <link rel="stylesheet" href="css/common.css">
    <link rel="stylesheet" href="css/vuln_login.css">
</head>
<body>
    <?php
    include 'nav.php';
    echo getNavbar('vuln_login.php');
    ?>

    <div class="container">
        <div class="header">
            <div class="title">
                🔓 <div class="vulnerable-badge">LỖ HỔNG</div>
            </div>
            <div class="description">
                Biểu mẫu này cố tình dễ bị tấn công SQL injection
            </div>
        </div>

        <?php
        $result = null;
        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $conn = new mysqli('db', 'demo', 'demopass', 'demo');
            if ($conn->connect_error) {
                $error = "Kết nối cơ sở dữ liệu thất bại";
            } else {
                $u = $_POST['username'] ?? '';
                $p = $_POST['password'] ?? '';

                // ⚠️ LỖ HỔNG: Ghép chuỗi trực tiếp
                $sql = "SELECT * FROM users WHERE username = '$u' AND password = '$p' LIMIT 1";
                $res = $conn->query($sql);

                if ($res && $res->num_rows > 0) {
                    $row = $res->fetch_assoc();
                    $result = "Chào mừng quay lại, " . htmlspecialchars($row['username']) . "! 🎉";
                } else {
                    $error = "Thông tin đăng nhập không hợp lệ";
                }
                $conn->close();
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

            <div class="form-actions">
                <button type="submit" name="action" value="login" class="btn btn-danger">
                    <span>⚠️</span>
                    <span>THỬ VI PHẠM</span>
                </button>

                <button type="button" onclick="showSQL()" class="btn btn-info">
                    <span>🔍</span>
                    <span>XEM SQL QUERY</span>
                </button>
            </div>
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

        <div class="payload-hint">
            <div class="payload-title">💡 Thử các payload SQL injection:</div>
            <div class="code-snippet">Tên đăng nhập: <code>admin' OR '1'='1</code></div>
            <div class="code-snippet">Mật khẩu: <code>batki</code></div>
        </div>

        <!-- SQL Display Modal -->
        <div id="sqlModal" class="sql-modal">
            <div class="sql-modal-content">
                <div class="sql-modal-header">
                    <h3>🔍 SQL Query Được Thực Thi</h3>
                    <button onclick="closeSQL()" class="close-modal">&times;</button>
                </div>
                <div class="sql-modal-body">
                    <div class="sql-warning">
                        ⚠️ CẢNH BÁO: Đây là SQL query nguy hiểm! Input chưa được sanitize.
                    </div>
                    <div class="sql-display">
                        <div class="sql-label">Query sẽ thực thi:</div>
                        <div class="sql-code-display" id="sqlDisplay"></div>
                    </div>
                    <div class="sql-explanation">
                        <p><strong>Cách hoạt động:</strong></p>
                        <p>Input của bạn được ghép <strong>TRỰC TIẾP</strong> vào chuỗi SQL mà không có validation!</p>
                        <p>Kẻ tấn công có thể injection SQL commands vào database qua form này.</p>
                    </div>
                </div>
            </div>
        </div>

        <script>
        function showSQL() {
            const username = document.getElementById('username').value || '[tên_đăng_nhập_trống]';
            const password = document.getElementById('password').value || '[mật_khẩu_trống]';

            const sql = `SELECT * FROM users WHERE username = '${username}' AND password = '${password}' LIMIT 1`;

            document.getElementById('sqlDisplay').textContent = sql;
            document.getElementById('sqlModal').style.display = 'block';
        }

        function closeSQL() {
            document.getElementById('sqlModal').style.display = 'none';
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('sqlModal');
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        }
        </script>


    </div>
</body>
</html>
