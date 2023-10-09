# Coinmarketcap API

This domain provides a client to call the Coinmarketcap API.

## Docs

[https://coinmarketcap.com/api/documentation/v1/](https://coinmarketcap.com/api/documentation/v1/)

## List of used calls

List of all API calls by this application.

### `quotes`

We calculate limits in daily/weekly/monthly period.

Credit: `1 credit / 100 symbols`

- once a day
- once a week
- once a month

Per month `~  7500 credits (worst case scenario)`

### `map`

When indexing currencies we need to download
the map of cryptocurrencies.

Credit: `1 credit`

- once a day

Per month `~ 30 credits`

### `fiatMap`

When indexing currencies we need to download
the map of fiat currencies.

Credit: `1 credit`

- once a day

Per month `~ 30 credits`

### `latestGlobalMetrics`

When showing dashboard we need to download
the current global metrics.

Credit: `1 credit`

- once a day

Per month `~ 30 credits`
