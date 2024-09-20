<?php
session_start();
if (isset($_SESSION['success_message'])) {
    echo '<p style="color: green;">' . $_SESSION['success_message'] . '</p>';
    // Unset the success message to avoid displaying it again after refresh
    unset($_SESSION['success_message']);
}
?>
<!-- Add any other content you want to display on the success page -->
