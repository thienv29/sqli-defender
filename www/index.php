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
            <div class="subtitle">Học SQL Injection Qua Thực Hành An Toàn</div>
        </header>

        <div class="warning">
            <strong>⚠️ CẢNH BÁO AN NINH</strong><br>
            Các demo này chỉ dành cho mục đích giáo dục.
            Chạy trong môi trường cô lập như container Docker.
            KHÔNG thử trên hệ thống production hoặc shared hosting.
        </div>

        <div class="demo-content">
            <div class="left-column">
                <div class="section">
                    <h2>🚀 Các Demo SQL Injection</h2>
                    <div class="demo-grid">
                        <div class="demo-card vulnerable">
                            <div class="demo-icon">🔓</div>
                            <div class="demo-title">Login Bypass</div>
                            <div class="demo-desc">Authentication bypass qua string concatenation</div>
                            <a href="vuln_login.php" class="demo-link">Thử Ngay</a>
                        </div>

                        <div class="demo-card safe">
                            <div class="demo-icon">🛡️</div>
                            <div class="demo-title">Login Bảo Mật</div>
                            <div class="demo-desc">PDO prepared statements - an toàn</div>
                            <a href="safe_login.php" class="demo-link">Thử Ngay</a>
                        </div>

                        <div class="demo-card vulnerable">
                            <div class="demo-icon">🔍</div>
                            <div class="demo-title">UNION Attack</div>
                            <div class="demo-desc">Data leakage qua UNION-based injection</div>
                            <a href="search.php" class="demo-link">Thử Ngay</a>
                        </div>

                        <div class="demo-card vulnerable">
                            <div class="demo-icon">👁️</div>
                            <div class="demo-title">Blind SQLi</div>
                            <div class="demo-desc">Boolean-based inference attacks</div>
                            <a href="blind.php" class="demo-link">Thử Ngay</a>
                        </div>
                    </div>
                </div>

                <div class="section">
                    <h2>📚 Kịch Bản Tấn Công Thực Tế</h2>
                    <div class="scenario-grid">
                        <div class="scenario-card">
                            <div class="scenario-title">1. Bypass Authentication</div>
                            <p>Sử dụng <code>vuln_login.php</code>:</p>
                            <div class="code-block">
    Username: <code>' OR '1'='1 --</code><br>
    Password: <code>bất kỳ</code>
                            </div>
                        </div>

                        <div class="scenario-card">
                            <div class="scenario-title">2. Data Exfiltration</div>
                            <p>Sử dụng <code>search.php</code>:</p>
                            <div class="code-block">
    <code>' UNION SELECT username, password FROM users --</code>
                            </div>
                        </div>

                        <div class="scenario-card">
                            <div class="scenario-title">3. Blind Data Extraction</div>
                            <p>Sử dụng <code>blind.php</code>:</p>
                            <div class="code-block">
    <code>1 AND substring(password,1,1)='a' FROM users WHERE id=1</code>
                            </div>
                        </div>

                        <div class="scenario-card">
                            <div class="scenario-title">4. Safe Comparison</div>
                            <p>Test payload trên <code>safe_login.php</code> - sẽ thất bại do prepared statements.</p>
                        </div>
                    </div>
                </div>

                <div class="section">
                    <h2>🛡️ Phòng Thủ Đa Lớp</h2>
                    <div class="defense-grid">
                        <div class="defense-item">
                            <strong>🏗️ Application Layer</strong>
                            <p>Prepared statements, input validation, parameterized queries</p>
                        </div>

                        <div class="defense-item">
                            <strong>🗄️ Database Layer</strong>
                            <p>Least privilege accounts, stored procedures, no dynamic SQL</p>
                        </div>

                        <div class="defense-item">
                            <strong>🌐 Network Layer</strong>
                            <p>WAF, input sanitization, rate limiting</p>
                        </div>

                        <div class="defense-item">
                            <strong>📊 Operational Layer</strong>
                            <p>Logging, monitoring, security audits, patch management</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="right-column">
                <h3 style="color: #dc3545; margin-bottom: 20px;">🔍 SQL INJECTION LÀ GÌ?</h3>
                <p><strong>SQL Injection (SQLi)</strong> là lỗ hổng bảo mật web phổ biến nhất, xếp hạng #1 trong OWASP Top 10.</p>

                <p>Lỗ hổng xảy ra khi ứng dụng web không validate input từ người dùng trước khi ghép vào SQL query. Kẻ tấn công có thể injection malicious SQL code, thay đổi logic query hoặc extract data nhạy cảm.</p>

                <p>Nguyên nhân chính: Ghép string trực tiếp vào SQL thay vì sử dụng prepared statements.</p>

                <h3 style="color: #dc3545; margin-top: 30px;">🛠️ CÁC LOẠI SQL INJECTION</h3>
                <ul>
                    <li><strong>Classical/In-band:</strong> Kết quả trực tiếp trong response (UNION, error-based)</li>
                    <li><strong>Blind/Inferential:</strong> Phải suy đoán từ behavior (boolean-based, time-based)</li>
                    <li><strong>Out-of-band:</strong> Data sent qua different channel (DNS, HTTP requests)</li>
                </ul>

                <h3 style="color: #dc3545; margin-top: 30px;">⚠️ TÌNH HUỐNG THỰC TẾ NỔI TIẾNG</h3>
                <div class="real-case">
                    <strong>2008 - Heartland Payment Systems:</strong><br>
                    Breach lớn nhất lịch sử, 130 triệu thẻ tín dụng compromised qua SQLi. Chi phí khắc phục > $300M.
                </div>
                <div class="real-case">
                    <strong>2011 - Sony Pictures:</strong><br>
                    77 triệu accounts bị steal. Hacker North Korean liên quan, breach dẫn đến class-action lawsuits.
                </div>
                <div class="real-case">
                    <strong>2014 - Yahoo:</strong><br>
                    500 triệu accounts compromised qua SQLi trong Yahoo Voices. Breach che giấu đến 2016.
                </div>
                <div class="real-case">
                    <strong>2017 - Equifax:</strong><br>
                    147 triệu personal records exposed. SQLi trong application chưa patch Apache Struts.
                </div>
                <div class="real-case">
                    <strong>Continuous WordPress Attacks:</strong><br>
                    Hàng triệu sites compromised hàng năm qua vulnerable plugins sử dụng dynamic SQL.
                </div>

                <h3 style="color: #dc3545; margin-top: 30px;">💰 TÁC ĐỘNG VÀ THIỆT HẠI</h3>
                <ul>
                    <li><strong>Data Breach:</strong> Leaked sensitive information (PII, financial data, credentials)</li>
                    <li><strong>Financial Loss:</strong> Recovery costs, fines, legal fees, lost revenue</li>
                    <li><strong>Reputational Damage:</strong> Loss of customer trust, brand damage</li>
                    <li><strong>Regulatory Fines:</strong> GDPR ($20M), CCPA, PCI DSS violations</li>
                    <li><strong>Further Attacks:</strong> Initial SQLi dẫn đến ransomware, lateral movement</li>
                </ul>

                <h3 style="color: #28a745; margin-top: 30px;">🛡️ PHÒNG CHỐNG HIỆU QUẢ</h3>
                <ul>
                    <li><strong>Primary Defense:</strong> Use prepared statements and parameterized queries ONLY</li>
                    <li><strong>Input Validation:</strong> Whitelist input, type checking, length limits</li>
                    <li><strong>ORM Frameworks:</strong> Frameworks prevent injection automatically</li>
                    <li><strong>Web Application Firewall:</strong> Block malicious patterns at network level</li>
                    <li><strong>Least Privilege:</strong> Database users với minimum required permissions</li>
                    <li><strong>Error Handling:</strong> Không expose SQL errors to users</li>
                    <li><strong>Regular Security Audits:</strong> Automated scanning, penetration testing</li>
                </ul>

                <h4 style="color: #28a745;">Prepared Statements - Best Practice:</h4>
                <p>Sử dụng PDO hoặc mysqli prepared statements thay vì string concatenation. Data được bind riêng biệt, SQL engine biết rõ boundary giữa code và data.</p>

                <p><strong>Kết luận:</strong> SQL injection dễ phòng ngừa nhưng hậu quả thảm hại. 90% vulnerabilities có thể fix bởi prepared statements đúng cách!</p>

                <h3 style="color: #17a2b8; margin-top: 30px;">🔧 CÔNG CỤ KIỂM TRA</h3>
                <ul>
                    <li><strong>sqlmap:</strong> Automated SQL injection tool</li>
                    <li><strong>OWASP ZAP:</strong> Web application security scanner</li>
                    <li><strong>Burp Suite:</strong> Manual testing and scanning</li>
                    <li><strong>DVWA:</strong> Damn Vulnerable Web Application for practice</li>
                    <li><strong>PortSwigger Labs:</strong> Free SQL injection training</li>
                </ul>
            </div>
        </div>

        <div class="footer">
            <p style="opacity: 0.8;">
                Phát triển với ❤️ bằng PHP, MySQL & Docker • Giáo dục Bảo mật Thông tin
            </p>
        </div>
    </div>
</body>
</html>
