<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Demo SQL Injection Boolean Ù</title>
    <link rel="stylesheet" href="css/common.css">
    <link rel="stylesheet" href="css/blind.css">
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar">
        <a href="index.php" class="navbar-brand">🔒 SQLi Defender</a>
        <ul class="navbar-nav">
            <li><a href="vuln_login.php">🔓 Bypass đăng nhập</a></li>
            <li><a href="safe_login.php">🛡️ Phiên bản an toàn</a></li>
            <li><a href="search.php">🔍 UNION SQLi</a></li>
            <li><a href="blind.php" class="active">👁️ Blind SQLi</a></li>
        </ul>
    </nav>

    <div class="container">
        <div class="header">
            <div class="title">
                👁️ <div class="vulnerable-badge">BLIND SQLi</div>
            </div>
            <div class="description">
                Demo tấn công SQL injection boolean-based mò mẫm
            </div>
        </div>

        <div class="user-info">
            <div class="user-info-title">👥 Người dùng test trong cơ sở dữ liệu:</div>
            <div class="user-list">
                <div class="user-item">
                    <div class="user-id">ID: 1</div>
                    <div class="user-name">Alice</div>
                    <div class="user-role">Vai trò: user</div>
                </div>
                <div class="user-item">
                    <div class="user-id">ID: 2</div>
                    <div class="user-name">Bob</div>
                    <div class="user-role">Vai trò: admin</div>
                </div>
            </div>
        </div>

        <div class="check-form">
            <form method="get">
                <div class="form-group">
                    <label for="id" class="form-label">Kiểm tra ID người dùng:</label>
                    <input type="text" id="id" name="id" class="form-input"
                           placeholder="Nhập ID hoặc chèn SQL..."
                           value="<?php echo isset($_GET['id']) ? htmlspecialchars($_GET['id']) : ''; ?>">

                    <div class="form-actions">
                        <button type="submit" name="action" value="check" class="btn btn-primary">
                            <span>👁️</span>
                            <span>Kiểm Tra Blind</span>
                            <span>🔍</span>
                        </button>

                        <button type="button" onclick="showSQL()" class="btn btn-info">
                            <span>👁️</span>
                            <span>XEM SQL QUERY</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <div class="result-section">
            <?php
            if (isset($_GET['id'])) {
                $id = $_GET['id'];
                $conn = new mysqli('db', 'demo', 'demopass', 'demo');

                if ($conn->connect_error) {
                    echo '<div class="result-not-admin"><div class="result-icon">❌</div><div class="result-title">Lỗi Cơ Sở Dữ Liệu</div><div class="result-description">Kết nối thất bại</div></div>';
                } else {
                    // ⚠️ LỖ HỔNG: Ghép chuỗi trực tiếp trong WHERE
                    $sql = "SELECT id FROM users WHERE id = $id AND role = 'admin'";
                    $res = $conn->query($sql);

                    if ($res && $res->num_rows > 0) {
                        echo '<div class="result-admin">';
                        echo '<div class="result-icon">👑</div>';
                        echo '<div class="result-title">User ID ' . htmlspecialchars($id) . ' là ADMIN!</div>';
                        echo '<div class="result-description">Điều kiện được đánh giá là TRUE</div>';
                        echo '</div>';
                    } else {
                        echo '<div class="result-not-admin">';
                        echo '<div class="result-icon">👤</div>';
                        echo '<div class="result-title">User ID ' . htmlspecialchars($id) . ' KHÔNG phải admin</div>';
                        echo '<div class="result-description">Điều kiện được đánh giá là FALSE</div>';
                        echo '</div>';
                    }
                    $conn->close();
                }
            }
            ?>
        </div>

        <div class="payloads-section">
            <div class="payloads-title">💡 Payload Boolean Blind SQLi:</div>
            <div class="payloads-grid">
                <div class="payload-item">
                    <div class="payload-label">🟢 Truy vấn bình thường:</div>
                    <div class="payload-code">id=2</div>
                    <div class="payload-expected">Bob là admin → TRUE</div>
                </div>

                <div class="payload-item">
                    <div class="payload-label">🔴 Luận đề đúng vĩnh viễn:</div>
                    <div class="payload-code">2 OR 1=1</div>
                    <div class="payload-expected">Luôn hiện admin</div>
                </div>

                <div class="payload-item">
                    <div class="payload-label">⚪ Mâu thuẫn (Luôn FALSE):</div>
                    <div class="payload-code">2 AND 1=2</div>
                    <div class="payload-expected">Không bao giờ hiện admin</div>
                </div>

                <div class="payload-item">
                    <div class="payload-label">🔍 Extract phiên bản DB:</div>
                    <div class="payload-code">2 AND substring((SELECT @@version),1,1)='8'</div>
                    <div class="payload-expected">TRUE nếu MySQL 8.x</div>
                </div>

                <div class="payload-item">
                    <div class="payload-label">📊 Extract ký tự mật khẩu:</div>
                    <div class="payload-code">2 AND substring((SELECT password FROM users WHERE id=1),1,1)='a'</div>
                    <div class="payload-expected">TRUE nếu mật khẩu Alice bắt đầu bằng 'a'</div>
                </div>
            </div>
        </div>

        <!-- SQL Display Modal -->
        <div id="sqlModal" class="sql-modal">
            <div class="sql-modal-content">
                <div class="sql-modal-header">
                    <h3>👁️ SQL Query Được Thực Thi</h3>
                    <button onclick="closeSQL()" class="close-modal">&times;</button>
                </div>
                <div class="sql-modal-body">
                    <div class="sql-warning">
                        ⚠️ CẢNH BÁO: Đây là SQL query nguy hiểm! Input chứa trong WHERE condition.
                    </div>
                    <div class="sql-display">
                        <div class="sql-label">Query sẽ thực thi:</div>
                        <div class="sql-code-display" id="sqlDisplay"></div>
                    </div>
                    <div class="sql-explanation">
                        <p><strong>Cách hoạt động:</strong></p>
                        <p>Input của bạn được nhồi vào <strong>WHERE id = ...</strong> query.</p>
                        <p>Blind attack dựa vào TRUE/FALSE để có thể extract bất kỳ data nào!</p>
                    </div>
                </div>
            </div>
        </div>

        <script>
        function showSQL() {
            const userId = document.getElementById('id').value || '[id_trống]';
            const sql = `SELECT id FROM users WHERE id = ${userId} AND role = 'admin'`;

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
