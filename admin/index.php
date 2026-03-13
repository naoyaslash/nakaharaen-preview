<?php
session_start();

// --- Configuration ---
define('ADMIN_PASSWORD', 'nakaharaen2026'); // Change this before deployment
define('DATA_DIR', __DIR__ . '/../data/');

// --- Authentication ---
$loggedIn = isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;

if (isset($_POST['logout'])) {
    session_destroy();
    header('Location: index.php');
    exit;
}

if (isset($_POST['password'])) {
    if ($_POST['password'] === ADMIN_PASSWORD) {
        $_SESSION['admin_logged_in'] = true;
        $loggedIn = true;
    } else {
        $loginError = 'パスワードが正しくありません';
    }
}

// --- Load current data ---
$availability = json_decode(file_get_contents(DATA_DIR . 'availability.json'), true);
$news = json_decode(file_get_contents(DATA_DIR . 'news.json'), true);

// --- Handle AJAX saves (from save.php redirects) ---
$successMessage = $_GET['success'] ?? null;
$activeTab = $_GET['tab'] ?? 'availability';
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>管理画面 — 中原苑</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
</head>
<body>

<?php if (!$loggedIn): ?>
  <!-- ===== LOGIN ===== -->
  <div class="login-wrapper">
    <div class="login-card">
      <h1>中原苑<span>管理画面</span></h1>
      <?php if (isset($loginError)): ?>
        <div class="alert alert-error"><?= htmlspecialchars($loginError) ?></div>
      <?php endif; ?>
      <form method="post">
        <label for="password">パスワード</label>
        <input type="password" id="password" name="password" placeholder="パスワードを入力" required autofocus>
        <button type="submit" class="btn-login">ログイン</button>
      </form>
    </div>
  </div>

<?php else: ?>
  <!-- ===== ADMIN PANEL ===== -->
  <header class="admin-header">
    <div class="admin-header-inner">
      <h1>中原苑 <span>管理画面</span></h1>
      <form method="post" class="logout-form">
        <button type="submit" name="logout" value="1" class="btn-logout">ログアウト</button>
      </form>
    </div>
  </header>

  <main class="admin-main">
    <?php if ($successMessage): ?>
      <div class="alert alert-success"><?= htmlspecialchars($successMessage) ?></div>
    <?php endif; ?>

    <!-- Tabs -->
    <div class="tabs">
      <a href="?tab=availability" class="tab <?= $activeTab === 'availability' ? 'active' : '' ?>">
        空き情報
      </a>
      <a href="?tab=news" class="tab <?= $activeTab === 'news' ? 'active' : '' ?>">
        お知らせ管理
      </a>
    </div>

    <!-- ===== TAB: Availability ===== -->
    <?php if ($activeTab === 'availability'): ?>
    <section class="panel">
      <h2>空き情報の更新</h2>
      <p class="panel-desc">数値を変更して「保存する」を押すと、サイトに即反映されます。</p>

      <form method="post" action="save.php">
        <input type="hidden" name="action" value="save_availability">

        <?php foreach ($availability['services'] as $si => $service): ?>
          <div class="form-group-card">
            <h3><?= htmlspecialchars($service['name']) ?></h3>
            <?php foreach ($service['slots'] as $sli => $slot): ?>
              <div class="input-row">
                <label><?= htmlspecialchars($slot['label']) ?></label>
                <div class="input-with-unit">
                  <input type="number" name="services[<?= $si ?>][slots][<?= $sli ?>][count]"
                         value="<?= (int)$slot['count'] ?>" min="0" max="999" required>
                  <span class="unit">名</span>
                </div>
                <input type="hidden" name="services[<?= $si ?>][slots][<?= $sli ?>][label]"
                       value="<?= htmlspecialchars($slot['label']) ?>">
              </div>
            <?php endforeach; ?>
            <input type="hidden" name="services[<?= $si ?>][id]" value="<?= htmlspecialchars($service['id']) ?>">
            <input type="hidden" name="services[<?= $si ?>][name]" value="<?= htmlspecialchars($service['name']) ?>">
          </div>
        <?php endforeach; ?>

        <button type="submit" class="btn-save">保存する</button>
      </form>

      <p class="last-updated">最終更新: <?= htmlspecialchars($availability['updatedAt']) ?></p>
    </section>
    <?php endif; ?>

    <!-- ===== TAB: News ===== -->
    <?php if ($activeTab === 'news'): ?>
    <section class="panel">
      <h2>お知らせ管理</h2>

      <!-- Add new -->
      <div class="form-group-card">
        <h3>新しいお知らせを追加</h3>
        <form method="post" action="save.php">
          <input type="hidden" name="action" value="add_news">
          <div class="input-row">
            <label>日付</label>
            <input type="date" name="date" value="<?= date('Y-m-d') ?>" required>
          </div>
          <div class="input-row">
            <label>種別</label>
            <select name="category" required>
              <option value="お知らせ">お知らせ</option>
              <option value="イベント">イベント</option>
            </select>
          </div>
          <div class="input-row">
            <label>タイトル</label>
            <input type="text" name="title" placeholder="お知らせのタイトル" required>
          </div>
          <div class="input-row">
            <label>内容</label>
            <textarea name="content" rows="3" placeholder="お知らせの内容（任意）"></textarea>
          </div>
          <div class="input-row">
            <label>リンク先</label>
            <input type="text" name="link" placeholder="/recruit など（任意）">
          </div>
          <button type="submit" class="btn-save">追加する</button>
        </form>
      </div>

      <!-- Existing news -->
      <div class="news-admin-list">
        <h3>登録済みのお知らせ</h3>
        <?php if (empty($news['items'])): ?>
          <p class="empty-message">お知らせはまだありません</p>
        <?php else: ?>
          <?php foreach ($news['items'] as $item): ?>
            <div class="news-admin-item">
              <div class="news-admin-info">
                <span class="news-admin-date"><?= htmlspecialchars($item['date']) ?></span>
                <span class="news-admin-category category-<?= $item['category'] === 'イベント' ? 'event' : 'info' ?>">
                  <?= htmlspecialchars($item['category']) ?>
                </span>
                <span class="news-admin-title"><?= htmlspecialchars($item['title']) ?></span>
              </div>
              <div class="news-admin-actions">
                <form method="post" action="save.php" class="inline-form">
                  <input type="hidden" name="action" value="delete_news">
                  <input type="hidden" name="id" value="<?= (int)$item['id'] ?>">
                  <button type="submit" class="btn-delete" onclick="return confirm('このお知らせを削除しますか？')">削除</button>
                </form>
              </div>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </section>
    <?php endif; ?>

  </main>

<?php endif; ?>

</body>
</html>
