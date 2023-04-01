# BT-API

This project serves as the backend API for my bachelor thesis web application.

## Initial setup

Firstly, make sure the `.env` file is created. If not, create it from `.env.example` and set up the variables.

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
