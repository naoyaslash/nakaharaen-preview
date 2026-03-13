<?php
session_start();

// --- Check authentication ---
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: index.php');
    exit;
}

define('DATA_DIR', __DIR__ . '/../data/');

$action = $_POST['action'] ?? '';

switch ($action) {
    case 'save_availability':
        saveAvailability();
        break;
    case 'add_news':
        addNews();
        break;
    case 'delete_news':
        deleteNews();
        break;
    default:
        header('Location: index.php');
        exit;
}

// --- Save Availability ---
function saveAvailability() {
    $services = $_POST['services'] ?? [];
    $data = [
        'updatedAt' => date('Y-m-d'),
        'services' => []
    ];

    foreach ($services as $service) {
        $slots = [];
        foreach ($service['slots'] as $slot) {
            $slots[] = [
                'label' => $slot['label'],
                'count' => max(0, (int)$slot['count'])
            ];
        }
        $data['services'][] = [
            'id' => $service['id'],
            'name' => $service['name'],
            'slots' => $slots
        ];
    }

    $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    file_put_contents(DATA_DIR . 'availability.json', $json);

    header('Location: index.php?tab=availability&success=' . urlencode('空き情報を更新しました'));
    exit;
}

// --- Add News ---
function addNews() {
    $newsFile = DATA_DIR . 'news.json';
    $news = json_decode(file_get_contents($newsFile), true);

    // Generate next ID
    $maxId = 0;
    foreach ($news['items'] as $item) {
        if ($item['id'] > $maxId) $maxId = $item['id'];
    }

    $newItem = [
        'id' => $maxId + 1,
        'date' => $_POST['date'] ?? date('Y-m-d'),
        'category' => $_POST['category'] ?? 'お知らせ',
        'title' => $_POST['title'] ?? '',
        'content' => $_POST['content'] ?? '',
        'link' => $_POST['link'] ?? ''
    ];

    // Add to beginning of array (newest first)
    array_unshift($news['items'], $newItem);

    $json = json_encode($news, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    file_put_contents($newsFile, $json);

    header('Location: index.php?tab=news&success=' . urlencode('お知らせを追加しました'));
    exit;
}

// --- Delete News ---
function deleteNews() {
    $newsFile = DATA_DIR . 'news.json';
    $news = json_decode(file_get_contents($newsFile), true);
    $deleteId = (int)($_POST['id'] ?? 0);

    $news['items'] = array_values(array_filter($news['items'], function($item) use ($deleteId) {
        return $item['id'] !== $deleteId;
    }));

    $json = json_encode($news, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    file_put_contents($newsFile, $json);

    header('Location: index.php?tab=news&success=' . urlencode('お知らせを削除しました'));
    exit;
}
