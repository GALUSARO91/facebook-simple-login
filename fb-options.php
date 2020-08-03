<?php

$app_id = $_POST['app_id'];
$app_secret = $_POST['app_secret'];

$option_values = json_encode(['app_id' => $app_id, 'app_secret' => $app_secret],JSON_FORCE_OBJECT);

update_option('fblogin', $option_values);


 ?>
