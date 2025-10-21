<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SQL Injection UNION - Rò Rỉ Dữ Liệu</title>
    <link rel="stylesheet" href="css/common.css">
    <link rel="stylesheet" href="css/search.css">
</head>
<body>
    <?php
    include 'nav.php';
    echo getNavbar('search.php');
    ?>

    <div class="container">
        <div class="header">
            <div class="title">
                🔍 <div class="vulnerable-badge">UNION SQLi</div>
            </div>
            <div class="description">
                Demo tấn công rò rỉ dữ liệu UNION SQL injection - Rò rỉ dữ liệu thông qua LIKE search
            </div>
        </div>

        <div class="demo-content">
            <div class="left-column">
                <div class="vulnerable-section">
                    <h3 class="red">🚨 PHƯƠNG PHÁP LỖ HỔNG</h3>
                    <p><strong>Ghép chuỗi trực tiếp trong LIKE query</strong></p>
                    <p><code>$sql = "SELECT username, email FROM users WHERE username LIKE '%$q%'";</code></p>
                    <p>UNION có thể rò rỉ dữ liệu từ nhiều bảng khác nhau.</p>
                </div>

                <div class="search-form">
                    <form method="get">
                        <div class="form-group">
                            <input type="text" name="q" class="form-input"
                                   placeholder="Tìm tên đăng nhập hoặc chèn UNION..."
                                   value="<?php echo isset($_GET['q']) ? htmlspecialchars($_GET['q']) : ''; ?>">
                            <div class="form-actions">
                                <button type="submit" name="action" value="search" class="btn btn-primary">
                                    <span>🔍</span>
                                    <span>Tìm Kiếm Union</span>
                                    <span>🕵️‍♂️</span>
                                </button>

                                <button type="button" onclick="showSQL()" class="btn btn-info">
                                    <span>🔍</span>
                                    <span>XEM SQL QUERY</span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="results-section">
                    <?php
                    if (isset($_GET['q'])) {
                        $q = $_GET['q'];
                        $conn = new mysqli('db', 'demo', 'demopass', 'demo');

                        if ($conn->connect_error) {
                            echo '<div class="no-results">Kết nối cơ sở dữ liệu thất bại</div>';
                        } else {
                            // ⚠️ LỖ HỔNG: Ghép chuỗi trực tiếp
                            $sql = "SELECT username, email FROM users WHERE username LIKE '%$q%'";
                            $res = $conn->query($sql);

                            $resultsCount = $res ? $res->num_rows : 0;
                            ?>
                            <div class="results-header">
                                <div class="results-title">Kết quả tìm kiếm cho: "<?php echo htmlspecialchars($q); ?>"</div>
                                <div class="results-count"><?php echo $resultsCount; ?> kết quả được tìm thấy</div>
                            </div>

                            <?php if ($res && $resultsCount > 0): ?>
                                <table class="data-table">
                                    <thead>
                                        <tr>
                                            <th>👤 Tên đăng nhập</th>
                                            <th>📧 Email</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while ($row = $res->fetch_assoc()): ?>
                                            <tr>
                                                <td class="data-username"><?php echo htmlspecialchars($row['username']); ?></td>
                                                <td class="data-email"><?php echo htmlspecialchars($row['email']); ?></td>
                                            </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            <?php else: ?>
                                <div class="no-results">
                                    Không tìm thấy kết quả cho truy vấn tìm kiếm của bạn.
                                </div>
                            <?php endif;

                            $conn->close();
                        }
                    }
                    ?>
                </div>

                <div class="payloads-section">
                    <div class="payloads-title">💡 Payload UNION SQL Injection:</div>
                    <ul class="payload-list">
                        <li class="payload-item">
                            <strong>UNION cơ bản:</strong>
                            <span class="payload-code">' UNION SELECT username, password FROM users --</span>
                        </li>
                        <li class="payload-item">
                            <strong>Nối dữ liệu:</strong>
                            <span class="payload-code">' UNION SELECT 1, concat(username, ':', password) FROM users --</span>
                        </li>
                        <li class="payload-item">
                            <strong>Database info:</strong>
                            <span class="payload-code">' UNION SELECT database(), version() --</span>
                        </li>
                        <li class="payload-item">
                            <strong>Dump schema:</strong>
                            <span class="payload-code">' UNION SELECT table_name, table_schema FROM information_schema.tables --</span>
                        </li>
                    </ul>
                    <p style="margin-top: 15px; font-size: 0.9em; color: rgba(255, 255, 255, 0.8);">
                        <strong>Lưu ý:</strong> Query gốc có 2 cột (username, email). UNION phải match số cột và types này.
                    </p>
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
                            ⚠️ CẢNH BÁO: Đây là SQL query nguy hiểm! Input chứa trong LIKE pattern.
                        </div>
                        <div class="sql-display">
                            <div class="sql-label">Query sẽ thực thi:</div>
                            <div class="sql-code-display" id="sqlDisplay"></div>
                        </div>
                        <div class="sql-explanation">
                            <p><strong>Cách hoạt động:</strong></p>
                            <p>Input được nhồi vào <strong>LIKE '%...%'</strong> query.</p>
                            <p>UNION attack có thể extract data từ tất cả tables!</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="right-column">
                <h3 style="color: #dc3545; margin-bottom: 20px;">🔍 CÁCH HOẠT ĐỘNG - UNION SQL INJECTION</h3>
                <p><strong>UNION</strong> kết hợp kết quả từ nhiều SELECT queries thành một tập kết quả duy nhất. Kẻ tấn công khai thác để append SQL queries độc hại.</p>

                <h4 style="color: #dc3545;">Cơ Chế Tấn Công:</h4>
                <ol>
                    <li><strong>Comment Out:</strong> Dùng <code>--</code> để đóng query gốc</li>
                    <li><strong>UNION SELECT:</strong> Thêm query độc hại trả về data nhạy cảm</li>
                    <li><strong>Match Columns:</strong> Đảm bảo số cột và types giống với query gốc</li>
                    <li><strong>Extract Data:</strong> Rò rỉ thông tin từ database</li>
                </ol>

                <p><strong>Ví dụ:</strong> Input <code>' UNION SELECT username, password FROM users --</code></p>
                <p>→ Query trở thành: <code>SELECT ... LIKE '%' UNION SELECT username, password FROM users -- %'</code></p>

                <h3 style="color: #dc3545; margin-top: 30px;">🛠️ CÁC UNION ATTACKS NÂNG CAO</h3>
                <ul>
                    <li><strong>Data Dumping:</strong> UNION SELECT email, password FROM admins</li>
                    <li><strong>Information Schema:</strong> UNION SELECT table_name, column_name FROM information_schema.columns</li>
                    <li><strong>File Reading:</strong> UNION SELECT LOAD_FILE('/etc/passwd'), null</li>
                    <li><strong>Command Execution:</strong> Sau UNION có thể chèn INTO OUTFILE hoặc stacked queries</li>
                    <li><strong>Blind UNION:</strong> Nghịch đảo logic khi không thể voir kết quả trực tiếp</li>
                </ul>

                <h4 style="color: #dc3545;">Điều Kiện Thành Công UNION:</h4>
                <ul>
                    <li>Số cột phải giống nhau giữa queries gốc và injected</li>
                    <li>Data types của cột phải tương thích</li>
                    <li>Không có keyword LIMIT hoặc ORDER BY cản trở trong chậm query trả về results trước</li>
                    <li>Application phải hiển thị UNION results</li>
                </ul>

                <h3 style="color: #dc3545; margin-top: 30px;">⚠️ TÌNH HUỐNG THỰC TẾ ĐÃ XẢY RA</h3>
                <div class="real-case">
                    <strong>2009 - Heartland Payment Systems:</strong><br>
                    Hacker dùng SQL injection UNION để steal 130 million credit card numbers. Công ty phá sản sau vụ việc.
                </div>
                <div class="real-case">
                    <strong>2016 - LinkedIn Breach:</strong><br>
                    Hacker publish 167 million email addresses and passwords trên dark web sau khi crack hashes from collected data via SQL injection.
                </div>
                <div class="real-case">
                    <strong>2018 - Facebook DataLeak:</strong><br>
                    50 million user profiles exposed qua misconfigured Elasticsearch API kết hợp UNION-style attacks.
                </div>
                <div class="real-case">
                    <strong>Hacker Forums:</strong><br>
                    Hàng triệu WordPress sites bị compromised qua UNION SQLi trong outdated plugins, dẫn đến data leaks và malware distribution.
                </div>

                <h3 style="color: #dc3545; margin-top: 30px;">💰 THIỆT HẠI TỪ UNION ATTACKS</h3>
                <ul>
                    <li><strong>Full Database Dump:</strong> Toàn bộ dữ liệu users, orders, financial records</li>
                    <li><strong>System Information:</strong> Server info, schema, other databases</li>
                    <li><strong>File System Access:</strong> Read configuration files, source code</li>
                    <li><strong>Further Attacks:</strong> Privilege escalation, command execution</li>
                    <li><strong>Network Compromisation:</strong> Pivot to internal systems</li>
                </ul>

                <h3 style="color: #28a745; margin-top: 30px;">🛡️ PHÒNG CHỐNG UNION SQL INJECTION</h3>
                <ul>
                    <li><strong>Prepared Statements:</strong> Không bao giờ ghép chuỗi - dùng parameterized queries</li>
                    <li><strong>Input Validation:</strong> Whitelist allowed characters, type validation</li>
                    <li><strong>Stored Procedures:</strong> Predefined queries không cho phép ad-hoc injections</li>
                    <li><strong>ORM Frameworks:</strong> Laravel Eloquent, Django ORM tự động escape</li>
                    <li><strong>WAF Rules:</strong> Block UNION keywords trong input</li>
                    <li><strong>Least Privilege:</strong> Database user không có quyền SELECT information_schema</li>
                    <li><strong>Error Handling:</strong> Không hiển thị SQL errors cho users</li>
                    <li><strong>Regular Security Audits:</strong> Scan vulnerability routinely</li>
                </ul>

                <p><strong>Kết Luận:</strong> UNION injection cho phép attackers read toàn bộ database. Cách phòng ngừa duy nhất là không ghép string vào SQL!</p>
            </div>
        </div>

        <script>
        function showSQL() {
            const query = document.querySelector('input[name="q"]').value || '[từ_khoá_trống]';
            const sql = `SELECT username, email FROM users WHERE username LIKE '%${query}%'`;

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
