<?php
    require '../required.php';

    $am = new PdoApiKeyManager();

	echo $am->getNumberKeysAvailable();
?>
