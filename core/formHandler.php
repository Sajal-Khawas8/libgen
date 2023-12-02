<?php
if (isset($_POST['login'])) {
    header("Location: /app/dashboard");
} else {
    echo "fail";
}
?>