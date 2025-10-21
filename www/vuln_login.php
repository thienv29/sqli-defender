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
                Demo đăng nhập lỗ hổng SQL injection - So sánh với phương pháp bảo mật bên trái
            </div>
        </div>

        <div class="demo-content">
            <div class="left-column">
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

                <div class="vulnerable-section">
                    <h3 class="red">🚨 PHƯƠNG PHÁP LỖ HỔNG</h3>
                    <p><strong>Ghép chuỗi trực tiếp vào SQL query</strong></p>
                    <p><code>SELECT * FROM users WHERE username = '$u' AND password = '$p' LIMIT 1</code></p>
                </div>

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
                    <div class="code-snippet">Tên đăng nhập: <code>' OR '1'='1 --</code> (Bypass login)</div>
                    <div class="code-snippet">Tên đăng nhập: <code>admin' UNION SELECT 1, version() --</code> (Extract version)</div>
                    <div class="code-snippet">Tên đăng nhập: <code>admin'; DROP TABLE users; --</code></div>
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
            </div>

            <div class="right-column">
                <h3 style="color: #dc3545; margin-bottom: 20px;">🔍 CÁCH HOẠT ĐỘNG</h3>
                <p><strong>SQL Injection</strong> xảy ra khi ứng dụng web không validate input từ người dùng trước khi ghép vào SQL query.</p>
                <p>Kẻ tấn công có thể làm thay đổi logic của query bằng cách chèn SQL commands độc hại.</p>
                <p>Trong đoạn code trên, input <code>$u</code> và <code>$p</code> được ghép trực tiếp vào string SQL, tạo lỗ hổng nghiêm trọng.</p>

                <h3 style="color: #dc3545; margin-top: 30px;">🛠️ CÁC TẤN CÔNG THỰC TẾ</h3>
                <ul>
                    <li><strong>Bypass Authentication:</strong> Tạo điều kiện luôn đúng bằng <code>OR '1'='1</code></li>
                    <li><strong>Data Exfiltration:</strong> Sử dụng <code>UNION SELECT</code> để trích xuất dữ liệu</li>
                    <li><strong>Arbitrary Commands:</strong> Chèn lệnh như <code>DROP TABLE</code>, <code>UPDATE</code> thay đổi dữ liệu</li>
                    <li><strong>Blind SQLi:</strong> Khi response không hiện data, đồ đoán dựa trên logic TRUE/FALSE</li>
                    <li><strong>Time-based Blind:</strong> Sử dụng điều kiện làm chậm response để liệt kê dữ liệu</li>
                </ul>

                <h3 style="color: #dc3545; margin-top: 30px;">⚠️ TÌNH HUỐNG THỰC TẾ ĐÃ XẢY RA</h3>
                <div class="real-case">
                    <strong>2011 - Sony Pictures Hack:</strong><br>
                    77 triệu tài khoản bị đánh cắp qua SQL injection. Hacker liên quan Triều Tiên, thiệt hại kinh tế hàng triệu USD.
                </div>
                <div class="real-case">
                    <strong>2014 - Heartland Payment Systems:</strong><br>
                    130 triệu thẻ tín dụng bị đánh cắp do lỗ hổng SQL injection. Công ty phải trả các khoản phạt lớn và chi phí khắc phục.
                </div>
                <div class="real-case">
                    <strong>2017 - Equifax Breach:</strong><br>
                    147 triệu hồ sơ cá nhân bị lộ thông tin nhạy cảm (SSN, ngày sinh) qua SQL injection trong ứng dụng web cũ.
                </div>
                <div class="real-case">
                    <strong>Tấn công không ngừng vào WordPress:</strong><br>
                    Hàng ngàn site WordPress bị hack hàng năm qua SQLi vì plugin và theme lỗi thời.
                </div>

                <h3 style="color: #dc3545; margin-top: 30px;">💰 THIỆT HẠI</h3>
                <ul>
                    <li><strong>Leak thông tin nhạy cảm:</strong> Mật khẩu, SSN, thẻ tín dụng, email</li>
                    <li><strong>Thiệt hại tài chính:</strong> Chi phí khắc phục, mất doanh thu, phạt vi phạm luật</li>
                    <li><strong>Uốn mất uy tín:</strong> Mất lòng tin của khách hàng, tỷ lệ hủy bỏ</li>
                    <li><strong>Rủi ro pháp lý:</strong> Quy trình điều tra, kiện tụng, phạt vượt quy định bảo vệ dữ liệu (GDPR)</li>
                    <li><strong>Nguy cơ bảo mật tiếp theo:</strong> Có thể dẫn đến ransomware hoặc tấn công mạng khác</li>
                </ul>

                <h3 style="color: #28a745; margin-top: 30px;">🛡️ GIẢI PHÁP</h3>
                <ul>
                    <li><strong>Prepared Statements:</strong> Sử dụng PDO prepared statements hoặc mysqli::prepare() với tham số ràng buộc</li>
                    <li><strong>Input Validation:</strong> Validate và sanitize tất cả input, whitelist ký tự cho phép</li>
                    <li><strong>Parameterized Queries:</strong> Không bao giờ ghép chuỗi trực tiếp vào SQL</li>
                    <li><strong>Escape Functions:</strong> MySQLi_real_escape_string() (chủ yếu cho text, không đủ an toàn nếu không dùng đúng)</li>
                    <li><strong>Tối thiểu hóa quyền:</strong> Database user chỉ có quyền cần thiết</li>
                    <li><strong>WAF - Web Application Firewall:</strong> Phát hiện và chặn SQL injection patterns</li>
                    <li><strong>Cập nhật và bảo mật:</strong> Luôn cập nhật CMS, frameworks, sử dụng HTTPS</li>
                    <li><strong>Hạn chế tính năng:</strong> Tắt các stored procedures nguy hiểm, hạn chế system commands</li>
                </ul>

                <p><strong>Kết luận:</strong> SQL injection dễ phòng ngừa nhưng hậu quả thảm hại. Luôn validate input và sử dụng prepared statements!</p>
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
