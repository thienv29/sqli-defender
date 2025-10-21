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
    <?php
    include 'nav.php';
    echo getNavbar('safe_login.php');
    ?>

    <div class="container">
        <div class="header">
            <div class="title">
                🛡️ <div class="safe-badge">AN TOÀN</div>
            </div>
            <div class="description">
                Demo đăng nhập an toàn dùng PDO prepared statements - Bảo vệ khỏi SQL injection
            </div>
        </div>

        <div class="safe-section">
            <h3 class="green">🛡️ PHƯƠNG PHÁP AN TOÀN</h3>
            <p><strong>PDO Prepared Statements với tham số ràng buộc</strong></p>
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

            <div class="form-actions">
                <button type="submit" class="btn btn-success">
                    <span>🛡️</span>
                    <span>Đăng Nhập Bảo Mật</span>
                    <span>🔒</span>
                </button>

                <button type="button" onclick="showEducation()" class="btn btn-info">
                    <span>🎓</span>
                    <span>TÌM HIỂU VỀ AN TOÀN</span>
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
            <div class="payload-title">💡 Thử các payload không còn nguy hiểm:</div>
            <div class="code-snippet"><code>' OR '1'='1 --</code> (Vô hiệu quả ở đây)</div>
            <div class="code-snippet"><code>admin'; DROP TABLE users; --</code> (Không thực thi lệnh độc)</div>
            <p style="margin-top: 10px; font-size: 0.9em;">
                Với prepared statements, payloads này chỉ là data, không phải SQL code!
            </p>
        </div>

        <div class="protection-note">
            <div class="protection-title">💡 Tại Sao An Toàn:</div>
            <p>Input được tham số hóa và xử lý riêng biệt. SQL engine biết đây chỉ là data, không thực thi như code.</p>
        </div>

        <!-- Education Modal -->
        <div id="educationModal" class="sql-modal">
            <div class="sql-modal-content">
                <div class="sql-modal-header">
                    <h3>🎓 PDO PREPARED STATEMENTS - Bảo Vệ SQL Injection</h3>
                    <button onclick="closeEducation()" class="close-modal">&times;</button>
                </div>
                <div class="sql-modal-body">
                    <div class="sql-education">
                        <h3 style="color: #28a745; margin-bottom: 20px;">🛡️ CÁCH HOẠT ĐỘNG - PDO PREPARED STATEMENTS</h3>
                        <p><strong>Prepared Statements</strong> tách biệt SQL code và data. Input luôn được treated là data, không bao giờ như SQL commands.</p>

                        <h4 style="color: #28a745;">Quy Trình:</h4>
                        <ol>
                            <li><strong>Chuẩn Bị (Prepare):</strong> SQL query được compile với placeholders (:u, :p)</li>
                            <li><strong>Ràng Buộc (Binding):</strong> Input bind vào placeholders như data thuần túy</li>
                            <li><strong>Thực Thi (Execute):</strong> Engine thay thế placeholders bằng data an toàn</li>
                        </ol>

                        <h4 style="color: #28a745;">Ví Dụ An Toàn:</h4>
                        <p><code>SELECT * FROM users WHERE username = :u AND password = :p</code></p>
                        <p>Tham số :u = <code>admin' OR '1'='1</code> → Chỉ là chuỗi ký tự, không phá vỡ query!</p>

                        <h3 style="color: #dc3545; margin-top: 30px;">🛠️ SO SÁNH VỚI PHƯƠNG PHÁP LỖ HỔNG</h3>
                        <table style="width: 100%; border-collapse: collapse; margin-top: 10px;">
                            <tr>
                                <th style="border: 1px solid #ccc; padding: 8px;">Aspect</th>
                                <th style="border: 1px solid #ccc; padding: 8px;">Lỗ Hổng</th>
                                <th style="border: 1px solid #ccc; padding: 8px;">An Toàn</th>
                            </tr>
                            <tr>
                                <td style="border: 1px solid #ccc; padding: 8px;">Ghép String</td>
                                <td style="border: 1px solid #ccc; padding: 8px; color: red;">"$sql = '$u'"</td>
                                <td style="border: 1px solid #ccc; padding: 8px; color: green;">bindParam()</td>
                            </tr>
                            <tr>
                                <td style="border: 1px solid #ccc; padding: 8px;">SQL Injection</td>
                                <td style="border: 1px solid #ccc; padding: 8px; color: red;">Có Thể</td>
                                <td style="border: 1px solid #ccc; padding: 8px; color: green;">Không Thể</td>
                            </tr>
                            <tr>
                                <td style="border: 1px solid #ccc; padding: 8px;">Performance</td>
                                <td style="border: 1px solid #ccc; padding: 8px;">Recompile mỗi lần</td>
                                <td style="border: 1px solid #ccc; padding: 8px;">Prepared một lần</td>
                            </tr>
                        </table>

                        <h3 style="color: #dc3545; margin-top: 30px;">⚠️ TÌNH HUỐNG THỰC TẾ ĐÃ XẢY RA (Do Không Dùng Prepared Statements)</h3>
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

                        <h3 style="color: #dc3545; margin-top: 30px;">💰 THIỆT HẠI TỪ SQL INJECTION</h3>
                        <ul>
                            <li><strong>Leak thông tin nhạy cảm:</strong> Mật khẩu, SSN, thẻ tín dụng, email</li>
                            <li><strong>Thiệt hại tài chính:</strong> Chi phí khắc phục, mất doanh thu, phạt vi phạm luật</li>
                            <li><strong>Uốn mất uy tín:</strong> Mất lòng tin của khách hàng</li>
                        </ul>

                        <h3 style="color: #28a745; margin-top: 30px;">🛡️ GIẢI PHÁP THÀNH CÔNG</h3>
                        <ul>
                            <li><strong>✅ Prepared Statements:</strong> PDO/MySQLi với bind parameters</li>
                            <li><strong>✅ Input Validation:</strong> Whitelist input, validate types</li>
                            <li><strong>✅ Escaping:</strong> mysqli_real_escape_string() (nhưng không thay thế prepared statements)</li>
                            <li><strong>✅ Least Privilege:</strong> Database user chỉ có quyền cần thiết</li>
                            <li><strong>✅ WAF:</strong> Web Application Firewall phát hiện patterns</li>
                            <li><strong>✅ ORM:</strong> Frameworks như Laravel, ASP.NET Entity Framework</li>
                        </ul>

                        <p><strong>Khuyến Nghị:</strong> Luôn sử dụng prepared statements cho mọi database operations. Đây là best practice không thể thiếu!</p>
                    </div>
                </div>
            </div>
        </div>

        <script>
        function showEducation() {
            document.getElementById('educationModal').style.display = 'block';
        }

        function closeEducation() {
            document.getElementById('educationModal').style.display = 'none';
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const eduModal = document.getElementById('educationModal');
            if (event.target == eduModal) {
                eduModal.style.display = 'none';
            }
        }
        </script>

    </div>
</body>
</html>
