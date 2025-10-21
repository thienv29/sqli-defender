<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Demo SQL Injection - Phòng Thủ An Ninh</title>
    <link rel="stylesheet" href="css/common.css">
    <link rel="stylesheet" href="css/index.css">
</head>
<body>
    <div class="container">
        <header>
            <div class="logo">🔒 SQLi Defender</div>
            <div class="subtitle">Code An Toàn vs. Code Lỗ Hổng</div>
        </header>

        <div class="warning">
            <strong>⚠️ CẢNH BÁO AN NINH</strong><br>
            Đây là demo các lỗ hổng SQL injection chỉ để giáo dục.
            Chỉ chạy trong môi trường cô lập như container local hoặc VM.
            Không thử nghiệm trên hệ thống không thuộc quyền quản lý của bạn.
        </div>

        <div class="section">
            <h2>🚀 Các Demo Có Sẵn</h2>
            <div class="demo-grid">
                <div class="demo-card vulnerable">
                    <div class="demo-icon">🔓</div>
                    <div class="demo-title">Đăng Nhập Lỗ Hổng</div>
                    <div class="demo-desc">Ghép chuỗi trực tiếp - dễ bị SQL injection</div>
                    <a href="vuln_login.php" class="demo-link">Thử Đăng Nhập Lỗ Hổng</a>
                </div>

                <div class="demo-card safe">
                    <div class="demo-icon">🛡️</div>
                    <div class="demo-title">Đăng Nhập An Toàn</div>
                    <div class="demo-desc">PDO prepared statements - được bảo vệ</div>
                    <a href="safe_login.php" class="demo-link">Thử Đăng Nhập An Toàn</a>
                </div>

                <div class="demo-card vulnerable">
                    <div class="demo-icon">🔍</div>
                    <div class="demo-title">Rò Rỉ Dữ Liệu</div>
                    <div class="demo-desc">Tấn công SQL injection dạng UNION</div>
                    <a href="search.php" class="demo-link">Thử Tấn Công UNION</a>
                </div>

                <div class="demo-card vulnerable">
                    <div class="demo-icon">👁️</div>
                    <div class="demo-title">SQL Injection Ù</div>
                    <div class="demo-desc">Tấn công boolean-based mò mẫm</div>
                    <a href="blind.php" class="demo-link">Thử SQL Injection Ù</a>
                </div>
            </div>
        </div>

        <div class="section">
            <h2>📚 Các Kịch Bản Demo</h2>
            <div class="scenario-grid">
                <div class="scenario-card">
                    <div class="scenario-title">1. Bypass Đăng Nhập</div>
                    <p>Sử dụng <code>vuln_login.php</code> để bypass xác thực:</p>
                    <div class="code-block">
Tên đăng nhập: <code>batki OR '1'='1</code><br>
Mật khẩu: <code>batki</code>
                    </div>
                </div>

                <div class="scenario-card">
                    <div class="scenario-title">2. Rò Rỉ Dữ Liệu</div>
                    <p>Sử dụng <code>search.php</code> để extract dữ liệu dạng UNION:</p>
                    <div class="code-block">
<code>' UNION SELECT ten_dang_nhap, mat_khau FROM users --</code>
                    </div>
                </div>

                <div class="scenario-card">
                    <div class="scenario-title">3. SQL Injection Ù</div>
                    <p>Sử dụng <code>blind.php</code> để khai thác boolean:</p>
                    <div class="code-block">
<code>1 AND substring((SELECT mat_khau FROM users WHERE id=1),1,1)='a'</code>
                    </div>
                </div>

                <div class="scenario-card">
                    <div class="scenario-title">4. So Sánh Phiên Bản An Toàn</div>
                    <p>Test cùng payload trên <code>safe_login.php</code> - sẽ thất bại vì PDO prepared statements có ràng buộc tham số.</p>
                </div>
            </div>
        </div>

        <div class="section">
            <h2>🛡️ Phòng Thủ Theo Từng Lớp</h2>
            <div class="defense-grid">
                <div class="defense-item">
                    <strong>🏗️ Mức Code</strong>
                    <p>Prepared statements, tham số hóa truy vấn, validate input</p>
                </div>

                <div class="defense-item">
                    <strong>🗄️ Mức Cơ Sở Dữ Liệu</strong>
                    <p>Tài khoản ít quyền nhất, tắt tính năng nguy hiểm</p>
                </div>

                <div class="defense-item">
                    <strong>🌐 Mức Ứng Dụng</strong>
                    <p>WAF (ModSecurity), escape input, whitelist</p>
                </div>

                <div class="defense-item">
                    <strong>📊 Mức Vận Hành</strong>
                    <p>Ghi log, phát hiện đăng nhập thất bại, audit</p>
                </div>
            </div>
        </div>

        <div class="footer">
            <p><strong class="tools">🔧 Công Cụ Test:</strong> sqlmap, OWASP ZAP, DVWA, SQLNinja</p>
            <p style="margin-top: 10px; opacity: 0.8;">
                Phát triển bằng PHP, MySQL & Docker • Nghiên Cứu An Ninh Giáo Dục
            </p>
        </div>
    </div>
</body>
</html>
