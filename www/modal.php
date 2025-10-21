<?php
/**
 * SQL Modal component for displaying SQL queries
 */

function getSQLModal($modalId = 'sqlModal', $title = 'SQL Query Được Thực Thi') {
    return <<<HTML
<!-- SQL Display Modal -->
<div id="{$modalId}" class="sql-modal">
    <div class="sql-modal-content">
        <div class="sql-modal-header">
            <h3>{$title}</h3>
            <button onclick="close{$modalId}()" class="close-modal">&times;</button>
        </div>
        <div class="sql-modal-body">
            <div class="sql-warning">
                ⚠️ CẢNH BÁO: Đây là SQL query nguy hiểm!
            </div>
            <div class="sql-display">
                <div class="sql-label">Query sẽ thực thi:</div>
                <div class="sql-code-display" id="{$modalId}Display"></div>
            </div>
            <div class="sql-explanation">
                <p><strong>Thông tin chi tiết sẽ được cập nhật tùy theo trang.</strong></p>
            </div>
        </div>
    </div>
</div>
HTML;
}

function getModalJavaScript($modalId = 'sqlModal', $closeFunction = '') {
    $closeFunc = $closeFunction ?: "close{$modalId}";
    return <<<JS
function show{$modalId}() {
    document.getElementById('{$modalId}').style.display = 'block';
}

function {$closeFunc}() {
    document.getElementById('{$modalId}').style.display = 'none';
}

// Close modal when clicking outside
document.getElementById('{$modalId}').addEventListener('click', function(event) {
    if (event.target == this) {
        {$closeFunc}();
    }
});
JS;
}
?>
