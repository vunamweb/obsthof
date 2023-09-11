<?php
global $morpheus;
$url =  $morpheus['url'] . 'page/calculation/index.html';
$output = '<div class="form_calculation">' . file_get_contents($url) . '</div>';
?>
