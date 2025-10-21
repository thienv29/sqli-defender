<?php
function getNavbar($currentPage = '') {
    $navItems = [
        'vuln_login.php' => '🔓 Bypass đăng nhập',
        'safe_login.php' => '🛡️ Phiên bản an toàn',
        'search.php' => '🔍 UNION SQLi',
        'blind.php' => '👁️ Blind SQLi'
    ];

    $navbar = '<nav class="navbar">' . PHP_EOL;
    $navbar .= '    <a href="index.php" class="navbar-brand">🔒 SQLi Defender</a>' . PHP_EOL;
    $navbar .= '    <ul class="navbar-nav">' . PHP_EOL;

    foreach ($navItems as $page => $label) {
        $active = ($page === $currentPage) ? ' class="active"' : '';
        $navbar .= '        <li><a href="' . $page . '"' . $active . '>' . $label . '</a></li>' . PHP_EOL;
    }

    $navbar .= '    </ul>' . PHP_EOL;
    $navbar .= '</nav>' . PHP_EOL;

    return $navbar;
}
?>
