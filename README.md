# Mintos Fund Transfer

A fund transfer REST application as a home assignment for Mintos
utilizing [Laravel](https://laravel.com/) framework.

## Technologies & Tools Used 🛠️
- Laravel (Easy backend set-up, provides necessary project toolset to quickly kickstart an application)
- PHPUnit (Code coverage with Feature (Integration) & Unit tests)
- Redis (For storing short term data to improve the performance of the code execution)
- Supervisor (For running dispatched tasks and cron-jobs)

## Tools
- Docker / Sail 🐳
- PHPStorm

## Prerequisites
Before we install & start-up the application, please make sure you have installed [Docker](https://www.docker.com/).

## Installation
1. Go to your preferred project directory and clone this repository & navigate to it.
```bash
git clone https://github.com/grunburg/mintos-transfter-app.git && cd mintos-transfter-app
```

2. Copy .env.example file as .env
```bash
cp .env.example .env
```

3. Create an account on [Exchangerate](https://exchangerate.host/) and get your own API access key. Then add it to your
.env variable.
```bash
XRT_ACCESS_KEY=e59f5d0e5a766d307e4357baaad0e863
```

4. Spin up a temporary docker container to install necessary dependencies.
```bash
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v $(pwd):/var/www/html \
    -w /var/www/html \
    laravelsail/php82-composer:latest \
    composer install --ignore-platform-reqs
```

5. Build the Sail configuration.
```bash
./vendor/bin/sail build --no-cache
```

6. Bring up the application containers.
```bash
./vendor/bin/sail up -d
```

7. Generate a new Laravel app key and run the migrations.
```bash
./vendor/bin/sail artisan key:generate
./vendor/bin/sail artisan migrate
```

8. Seed the database with fake users and to accounts to play with.
```bash
./vendor/bin/sail artisan db:seed
```

9. Import the rates by launching a command (otherwise, the rates are generated daily by scheduled job).
```bash
./vendor/bin/sail artisan rate:import
```

10. Looks like you're done, now get the endpoints and play around on [localhost:8000/api](http://localhost:8000/api). 🚀

## Tests
You can launch the Feature (Integration) & Unit tests by these commands:
```bash
./vendor/bin/sail artisan test --testsuite=Unit
./vendor/bin/sail artisan test --testsuite=Feature
```

## Endpoints
Application supports three different endpoints.

```
GET  : /api/account/{account_id}/transactions?limit=10&offset=0
GET  : /api/user/{user_id}/accounts
POST : /api/transfer (from_account_id: int, to_account_id: int, amount: int, curreny: string])
```
Or get the [Postman](https://www.postman.com/) collection [here](https://interstellar-crescent-465206.postman.co/workspace/Mintos~ca226b1e-56d7-4f5e-861f-ca230a0002ef/collection/9286277-7a341c2e-78d1-48b4-a6c7-754af961ca1d?action=share&creator=9286277)
& import it to your own client.


## Additional Notes 🗒️
Current implementation of rate import supports only single rate service but can be easily expanded on due to the
architecture & the currencies are: EUR, USD, GBP, AUD, CHF.

Additionally, for the fund transfer implementation, I used supervisor, a background screen service which powers the
jobs & scheduled jobs (i.e. daily rate import). Jobs are used because if something fails due to a bug in code, they can
later be re-run & also solves http request timeout if some kind of external fraud-check service is added.

Rates are resilient to 3rd party service unavailability due to them being imported daily within the app and clever
fallback mechanisms if daily import fails.