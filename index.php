<?php
// Шлях до JSON файлу
$jsonFile = 'poll.json';

// Завантажуємо дані з JSON-файлу
function loadPollData() {
    global $jsonFile;
    if (file_exists($jsonFile)) {
        $jsonData = file_get_contents($jsonFile);
        return json_decode($jsonData, true);
    }
    return [];
}

// Зберігаємо дані в JSON-файлі
function savePollData($data) {
    global $jsonFile;
    file_put_contents($jsonFile, json_encode($data, JSON_PRETTY_PRINT));
}

// Ініціалізація даних опитування, якщо ще немає
$data = loadPollData();
if (empty($data)) {
    $data = [
        "question" => "Яка ваша улюблена мова програмування?",
        "options" => ["PHP", "JavaScript", "Python", "Ruby"],
        "votes" => [0, 0, 0, 0]
    ];
    savePollData($data);
}

// Якщо отримано голос, оновлюємо результат
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['option'])) {
    $option = (int)$_POST['option'];
    if (isset($data['votes'][$option])) {
        $data['votes'][$option]++;
        savePollData($data);
    }
}

// Завантажуємо оновлені дані
$data = loadPollData();
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Опитування</title>
</head>
<body>
    <h1><?php echo htmlspecialchars($data['question']); ?></h1>
    
    <!-- Форма для голосування -->
    <form method="POST">
        <?php foreach ($data['options'] as $index => $option): ?>
            <label>
                <input type="radio" name="option" value="<?php echo $index; ?>" required>
                <?php echo htmlspecialchars($option); ?>
            </label><br>
        <?php endforeach; ?>
        <button type="submit">Проголосувати</button>
    </form>

    <h2>Результати:</h2>
    <ul>
        <?php foreach ($data['options'] as $index => $option): ?>
            <li><?php echo htmlspecialchars($option) . ": " . $data['votes'][$index] . " голосів"; ?></li>
        <?php endforeach; ?>
    </ul>
</body>
</html>
