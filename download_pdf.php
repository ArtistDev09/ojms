<?php
require('connection.php');

if (isset($_GET['id'])) {
    $journalID = intval($_GET['id']);
    $a = 10;
    $stmt = $conn->prepare("SELECT pdf, title FROM journal WHERE journalID = ?");
    $stmt->bind_param("i", $journalID);
    $stmt->execute();
    $stmt->bind_result($pdfContent, $title);
    $stmt->fetch();

    if ($pdfContent) {
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="' . htmlspecialchars($title) . '.pdf"');
        echo $pdfContent;

        $stmt->close();

    $sql = $conn->prepare("INSERT INTO click(count, journalID) VALUES(1, $journalID)");
    $sql->execute();
    $sql->close();

    } else {
        echo "PDF not found.";
    }
    
    
} else {
    echo "Invalid request.";
}
?>
