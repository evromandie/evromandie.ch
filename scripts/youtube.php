<?php

/**
 * Ce script télécharge les avatars des chaînes YouTube et met à jour les statistiques de vidéos et vues
 */

require __DIR__ . '/vendor/autoload.php';

use Symfony\Component\Yaml\Yaml;

// Formatte un nombre d'une manière similaire au compteur d'abonnés YouTube
function numberToDisplay($number)
{
    if ($number >= 1000000) {
        return floor($number / 100000) / 10 . 'M';
    }

    if ($number >= 1000) {
        return floor($number / 1000) . 'k';
    }

    return intval($number);
}

// Formatte un nombre d'une manière similaire au compteur d'abonnés YouTube
// Mais le conserve en valeur non abrégée pour pouvoir l'utiliser dans un tri
function numberToRaw($number)
{
    if ($number >= 1000000) {
        return floor($number / 100000) * 100000;
    }

    if ($number >= 1000) {
        return floor($number / 1000) * 1000;
    }

    return intval($number);
}

$channelsFile = __DIR__ . '/../_data/youtube_channels.yml';
$channelsFileContent = file_get_contents($channelsFile);
$channels = Yaml::parse($channelsFileContent);

$ids = [];
foreach ($channels as $i => $channel) {
    $ids[] = $channel['id'];

    // Supprime toutes les informations de statistiques pour avoir uniquement les nouvelles données retournées par l'API à la fin
    if (array_key_exists('stats', $channel)) {
        unset($channels[$i]['stats']);
    }
}

$config = require('config.php');
$apiKey = $config['google-api-key'];

$response = json_decode(file_get_contents('https://www.googleapis.com/youtube/v3/channels?part=snippet,statistics&id=' . implode(',', $ids) . '&fields=items(id,snippet/thumbnails/medium,statistics)&key=' . $apiKey), true);

foreach ($response['items'] as $index => $channel) {
    $id = $channel['id'];
    $url = $channel['snippet']['thumbnails']['medium']['url'];

    echo 'Téléchargement de l\'avatar pour ' . $id . PHP_EOL;

    // Copie l'avatar localement
    copy($url, __DIR__ . '/../media/youtube-avatars/' . $id . '.jpg');

    // Récupère les statistiques de la chaîne
    foreach ($channels as $i => $data) {
        if ($data['id'] !== $id) {
            continue;
        }

        $channels[$i]['stats'] = [
            'views' => numberToDisplay($channel['statistics']['viewCount']),
            'views_raw' => (int)numberToRaw($channel['statistics']['viewCount']),
            'subscribers' => numberToDisplay($channel['statistics']['subscriberCount']),
            'videos' => intval($channel['statistics']['videoCount']),
        ];

        break;
    }
}

$yaml = Yaml::dump($channels, 5, 2);

// Place les tirets sur la première ligne de l'élément de tableau au lieu d'une ligne à part
$yaml = str_replace("\n-\n  ", "\n- ", "\n$yaml");

// Supprime le retour à la ligne introduit par la méthode ci-dessus
$yaml = preg_replace("/^\n/", "", $yaml);

// Remplace le fichier source s'il a changé
if ($yaml !== $channelsFileContent) {
    echo 'Mise à jour du fichier des chaînes' . PHP_EOL;
    file_put_contents($channelsFile, $yaml);
} else {
    echo 'Aucun changement au fichier des chaînes' . PHP_EOL;
}
