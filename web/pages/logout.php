<?php
include "../helpers/api.php";

API::logout();
header("Location: ../index.php");
exit;
