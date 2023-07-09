# BT-API

This project serves as the backend API for my bachelor thesis web application.

## Initial setup

Firstly, make sure the `.env` file is created. If not, create it from `.env.example` and set up the variables.

Secondly, make sure the frontend application runs on the same domain as the API. This is because the API uses
Sanctum as the main auth provider.

For instance API would run `api.domain.com` and frontend would run on `domain.com`.

Setup `SESSION_DOMAIN` env value to the domain of the frontend and backend application. For instance `localhost`.

Setup `SANCTUM_STATEFUL_DOMAINS` env value to the frontend application domain, port included. For instance `localhost:3000`.

```bash
# install dependencies
$ sail composer install

# setup application key
$ sail art key:generate

# publish telescope assets
$ sail art telescope:publish

# install migrations
$ sail art migrate
```

## Commands

```bash
# format code
$ sail composer format
```
