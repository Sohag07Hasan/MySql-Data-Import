<?php
$id = (int)$_GET['id'];
wp_delete_post( $id, true );
$message = "<div class='updated'><p>Deleted</p></div>";