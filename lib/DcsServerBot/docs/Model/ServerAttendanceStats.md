# # ServerAttendanceStats

## Properties

Name | Type | Description | Notes
------------ | ------------- | ------------- | -------------
**current_players** | **int** | Current number of active players |
**unique_players_24h** | **int** | Unique players in last 24 hours |
**total_playtime_hours_24h** | **float** | Total playtime hours in last 24 hours |
**discord_members_24h** | **int** | Discord members who played in last 24 hours |
**unique_players_7d** | **int** | Unique players in last 7 days |
**total_playtime_hours_7d** | **float** | Total playtime hours in last 7 days |
**discord_members_7d** | **int** | Discord members who played in last 7 days |
**unique_players_30d** | **int** | Unique players in last 30 days |
**total_playtime_hours_30d** | **float** | Total playtime hours in last 30 days |
**discord_members_30d** | **int** | Discord members who played in last 30 days |
**daily_trend** | **array<string,mixed>[]** | Daily unique player counts for trend analysis | [optional]
**top_theatres** | [**\DcsServerBot\Model\TopTheatre[]**](TopTheatre.md) | Top theatres by playtime | [optional]
**top_missions** | [**\DcsServerBot\Model\TopMission[]**](TopMission.md) | Top missions by playtime | [optional]
**top_modules** | [**\DcsServerBot\Model\TopModule[]**](TopModule.md) | Top modules by playtime and usage | [optional]
**total_sorties** | **int** |  | [optional]
**total_kills** | **int** |  | [optional]
**total_deaths** | **int** |  | [optional]
**total_pvp_kills** | **int** |  | [optional]
**total_pvp_deaths** | **int** |  | [optional]

[[Back to Model list]](../../README.md#models) [[Back to API list]](../../README.md#endpoints) [[Back to README]](../../README.md)
