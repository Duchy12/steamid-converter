# steamid-converter
Crossconverts steamids between their variations(SteamID, SteamID64, SteamID3) \
Doesn't support custom urls since I find that useless for my use
## Request
```https://yourserver.com/convertID.php?id=YOURIDHERE```

## Returns
On successful conversion
```json
{
    "SteamID": "STEAM_0:0:736989607",
    "SteamID3": "[U:1:1473979214]",
    "SteamID64": "76561199434244942"
}
```
On invalid steamid
```json
{
    "error": "Invalid SteamID format"
}
```
On empty id param
```json
{
    "error":"ID parameter is missing or empty."
}
```