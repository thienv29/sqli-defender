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
    <?php
    include 'nav.php';
    echo getNavbar('blind.php');
    ?>

    <div class="container">
        <div class="header">
            <div class="title">
                👁️ <div class="vulnerable-badge">BLIND SQLi</div>
            </div>
            <div class="description">
                Demo tấn công SQL injection boolean-based mò mẫm - Extract data thông qua TRUE/FALSE logic
            </div>
        </div>

        <div class="vulnerable-section">
            <h3 class="red">🚨 PHƯƠNG PHÁP LỖ HỔNG</h3>
            <p><strong>Ghép chuỗi trực tiếp trong WHERE clause</strong></p>
            <p><code>$sql = "SELECT id FROM users WHERE id = $id AND role = 'admin'";</code></p>
            <p>Không yêu cầu hiển thị data, chỉ cần TRUE/FALSE response.</p>
        </div>
<!-- 
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
        </div> -->

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

                    <button type="button" onclick="showEducation()" class="btn btn-warning">
                        <span>🎓</span>
                        <span>TÌM HIỂU VỀ BLIND</span>
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
                    <div class="payload-code">2</div>
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
                    <div class="payload-label">🔍 Extract DB version:</div>
                    <div class="payload-code">2 AND substring((SELECT @@version),1,1)='8'</div>
                    <div class="payload-expected">TRUE nếu MySQL 8.x</div>
                </div>

                <div class="payload-item">
                    <div class="payload-label">📊 Extract mật khẩu:</div>
                    <div class="payload-code">2 AND substring((SELECT password FROM users WHERE id=1),1,1)='a'</div>
                    <div class="payload-expected">TRUE nếu mật khẩu Alice bắt đầu bằng 'a'</div>
                </div>

                <div class="payload-item">
                    <div class="payload-label">⏰ Time-based (nếu boolean không hoạt động):</div>
                    <div class="payload-code">2 AND IF(substring(database(),1,1)='t', SLEEP(5), 0)=0</div>
                    <div class="payload-expected">Response chậm 5s nếu database bắt đầu bằng 't'</div>
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
                        <p>Input nhồi vào <strong>WHERE id = ...</strong> query.</p>
                        <p>Blind attack dựa vào TRUE/FALSE để extract toàn bộ data!</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Education Modal -->
        <div id="educationModal" class="sql-modal">
            <div class="sql-modal-content">
                <div class="sql-modal-header">
                    <h3>🎓 BLIND SQL INJECTION - Chi Tiết Giải Thích</h3>
                    <button onclick="closeEducation()" class="close-modal">&times;</button>
                </div>
                <div class="sql-modal-body">
                    <div class="sql-education">
                        <h3 style="color: #dc3545; margin-bottom: 20px;">👁️ CÁCH HOẠT ĐỘNG - BLIND SQL INJECTION</h3>
                        <p><strong>Blind SQL Injection</strong> là dạng tấn công mà attacker không thể thấy kết quả query trực tiếp, nhưng có thể suy đoán thông tin dựa vào hành vi khác nhau của application.</p>

                        <h4 style="color: #dc3545;">Nguyên Lý:</h4>
                        <ul>
                            <li><strong>Boolean-based:</strong> Application trả về hai response khác nhau (TRUE/FALSE)</li>
                            <li><strong>Time-based:</strong> Application chậm trễ response dựa trên điều kiện</li>
                            <li><strong>Error-based:</strong> Trigger SQL errors để lộ thông tin</li>
                        </ul>

                        <p><strong>Ví dụ Boolean:</strong> Input <code>2 AND substring((SELECT password FROM users WHERE id=1),1,1)='a'</code></p>
                        <p>Nếu TRUE → Password bắt đầu bằng 'a'. Nếu FALSE → không phải 'a'.</p>

                        <h3 style="color: #dc3545; margin-top: 30px;">🛠️ LOẠI BLIND SQL INJECTION</h3>
                        <ul>
                            <li><strong>Content-based Boolean:</strong> Hai trang khác nhau cho TRUE/FALSE (như ví dụ này)</li>
                            <li><strong>HTTP Status Boolean:</strong> 200 OK cho TRUE, 500 Error cho FALSE</li>
                            <li><strong>Time-based Blind:</strong> SLEEP(5) nếu điều kiện đúng → response chậm</li>
                            <li><strong>Out-of-band:</strong> Trigger DNS/external requests để extract data</li>
                            <li><strong>Error-based Blind:</strong> Trigger controlled SQL errors lộ data bits-by-bits</li>
                        </ul>

                        <h4 style="color: #dc3545;">Binary Search Algorithm trong Blind SQLi:</h4>
                        <ol>
                            <li>Test length của string: <code>AND LENGTH(password)>10</code></li>
                            <li>Binary search từng character (ASCII 0-255)</li>
                            <li>Sử dụng SUBSTRING() hoặc MID() để extract từng vị trí</li>
                            <li>Automate với sqlmap hoặc custom scripts</li>
                        </ol>

                        <h3 style="color: #dc3545; margin-top: 30px;">⚠️ TÌNH HUỐNG THỰC TẾ ĐÃ XẢY RA</h3>
                        <div class="real-case">
                            <strong>2010 - MySQL.com Compromise:</strong><br>
                            Attacker dùng blind SQLi để extract database credentials, compromise customer's servers hosting millions of websites.
                        </div>
                        <div class="real-case">
                            <strong>2014 - Yahoo Voices Hack:</strong><br>
                            450,000 user credentials stolen qua blind SQL injection. Yahoo's infrastructure vulnerabilities chế giễu security practices.
                        </div>
                        <div class="real-case">
                            <strong>2017 - British Airways Breach:</strong><br>
                            400,000 payment cards compromised. Attackers used blind SQLi combined with card skimming malware.
                        </div>
                        <div class="real-case">
                            <strong>2019 - Capital One Cloud Breach:</strong><br>
                            100 million customer records exposed. Hacker exploited server-side request forgery combined with SQLi to access S3 buckets.
                        </div>
                        <div class="real-case">
                            <strong>Hacker Toolkits:</strong><br>
                            Blind SQLi widely used in automated tools như sqlmap, allowing attackers extract data from "secure" applications that don't leak data directly.
                        </div>

                        <h3 style="color: #dc3545; margin-top: 30px;">💰 THIỆT HẠI TỪ BLIND ATTACKS</h3>
                        <ul>
                            <li><strong>Stealthy Extraction:</strong> Không để lại logs rõ ràng, khó phát hiện</li>
                            <li><strong>Complete Data Compromise:</strong> Extract được tất cả data dù không hiển thị</li>
                            <li><strong>Infrastructure Pivot:</strong> Từ web app compromised, move laterally into internal systems</li>
                            <li><strong>Long-term Access:</strong> Install webshells hoặc backdoors qua file write primitives</li>
                            <li><strong>Zero-day Vulnerabilities:</strong> Bypass traditional defenses vì không match signatures</li>
                        </ul>

                        <h3 style="color: #28a745; margin-top: 30px;">🛡️ PHÒNG CHỐNG BLIND SQL INJECTION</h3>
                        <ul>
                            <li><strong>Prepared Statements:</strong> Tách biệt SQL code và data hoàn toàn</li>
                            <li><strong>Input Type Validation:</strong> Force integer types, reject non-numeric input</li>
                            <li><strong>Parameterized Stored Procedures:</strong> Định nghĩa query trước, ràng buộc parameters</li>
                            <li><strong>Web Application Firewall:</strong> Rules detect unusual SQL patterns, timing attacks</li>
                            <li><strong>Rate Limiting:</strong> Block brute-force enumeration attacks</li>
                            <li><strong>Logging & Monitoring:</strong> Detect suspicious query patterns, slow responses</li>
                            <li><strong>Database Hardening:</strong> Disable error messages, limit user privileges</li>
                            <li><strong>Security Headers:</strong> CSP, HSTS prevent content injection attacks</li>
                        </ul>

                        <p><strong>Kết Luận:</strong> Blind SQLi nguy hiểm vì khó phát hiện. Chúng bypass traditional filters vì không contain "OR 1=1" signatures. Chỉ prepared statements mới thực sự an toàn!</p>
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

        function showEducation() {
            document.getElementById('educationModal').style.display = 'block';
        }

        function closeEducation() {
            document.getElementById('educationModal').style.display = 'none';
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const sqlModal = document.getElementById('sqlModal');
            const eduModal = document.getElementById('educationModal');
            if (event.target == sqlModal) {
                sqlModal.style.display = 'none';
            } else if (event.target == eduModal) {
                eduModal.style.display = 'none';
            }
        }
        </script>

    </div>
</body>
</html>
