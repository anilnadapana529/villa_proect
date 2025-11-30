<?php
include __DIR__ . "/helpers/api.php";

API::logout();
header("Location: /");
exit;
