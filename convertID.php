<?php

// I feel like there's no point in sanitizing the input properly since we're not using it in a query
$id = $_GET['id'];
header('Content-Type: application/json');

// https://developer.valvesoftware.com/wiki/SteamID
// Converts Steam ID to all other types (stores them in an array)
function convertSteamID($steamID) {
    $steamID = trim($steamID); // Prolly not necessary but just in case

    // SteamID pattern (STEAM_X:Y:Z)
    $steamIDPattern = '/^STEAM_(\d+):([0-1]):(\d+)$/';

    // SteamID3 pattern ([U:1:XXXXXX])
    $steamID3Pattern = '/^\[U:1:(\d+)]$/';

    // SteamID64 pattern (17-digit number)
    $steamID64Pattern = '/^(\d{17})$/';

    if (preg_match($steamIDPattern, $steamID, $matches)) {
        if (strlen($matches[3]) > 10) {
            return [
                'error' => 'Invalid SteamID length'
            ];
        }
        $steamID3 = "[U:1:{$matches[3]}]";
        $steamID64 = ($matches[3] * 2) + 76561197960265728 + $matches[2];
        return [
            'SteamID' => $steamID,
            'SteamID3' => $steamID3,
            'SteamID64' => $steamID64
        ];
    } elseif (preg_match($steamID3Pattern, $steamID, $matches)) {
        if (strlen($matches[1]) > 10) {
            return [
                'error' => 'Invalid SteamID3 length'
            ];
        }
        $steamID64 = $matches[1] + 76561197960265728;
        $steamID = "STEAM_0:" . (($steamID64 - 76561197960265728) & 1) . ":" . (($steamID64 - 76561197960265728) >> 1);
        $steamID3 = "[U:1:" . ($steamID64 - 76561197960265728) . "]";
        return [
            'SteamID' => $steamID,
            'SteamID3' => $steamID3,
            'SteamID64' => $steamID64
        ];
    } elseif (preg_match($steamID64Pattern, $steamID)) {
        if (strlen($steamID) !== 17) {
            return [
                'error' => 'Invalid SteamID64 length'
            ];
        }
        $steamID64 = $steamID;
        $steamID = "STEAM_0:" . (($steamID64 - 76561197960265728) & 1) . ":" . (($steamID64 - 76561197960265728) >> 1);
        $steamID3 = "[U:1:" . ($steamID64 - 76561197960265728) . "]";
        return [
            'SteamID' => $steamID,
            'SteamID3' => $steamID3,
            'SteamID64' => $steamID64
        ];
    } else {
        return [
            'error' => 'Invalid SteamID format'
        ];
    }
}

// Dump it to json
function printSteamIDs($steamIDArray) {
    echo json_encode($steamIDArray, JSON_PRETTY_PRINT);
}


// Make sure the "id" parameter is set and not empty
if(isset($id) && !empty($id)) {
    $convertedIDs = convertSteamID($id);
    printSteamIDs($convertedIDs);
} else {
    $errorMessage = [
        'error' => 'ID parameter is missing or empty.'
    ];
    echo json_encode($errorMessage);
}

?>