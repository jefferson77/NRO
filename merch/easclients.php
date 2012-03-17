<?php
## différents clients
$clients = array(
    '1713,2346,2933,2929,2931' 	=> 'Carrefour',
    '2316' 						=> 'Champion',
    '2651,2928' 				=> 'GB EAS'
);

## clients GB
$revclients = array_flip($clients);
$gbs = $revclients['GB EAS']; ## pour les requetes SQL

$gbclients = explode(",", $gbs); ## pour les in_array

?>