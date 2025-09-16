<?php
header('Content-Type: application/json');
include 'config.php';

$stmt = $pdo->prepare("SELECT pc_id, lab_id, status, last_check FROM pc_status");
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

$currentTime = date('Y-m-d H:i:s');
foreach ($results as $pc) {
    if ($pc['status'] === 'online') {
        $update = $pdo->prepare("UPDATE pc_status SET last_check = ? WHERE pc_id = ?");
        $update->execute([$currentTime, $pc['pc_id']]);
        
        $archive = $pdo->prepare("INSERT INTO pc_status_archive (pc_id, lab_id, status, timestamp) VALUES (?, ?, ?, ?)");
        $archive->execute([$pc['pc_id'], $pc['lab_id'], $pc['status'], $currentTime]);
    }
}

echo json_encode($results);
?>