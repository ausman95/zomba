# SICO Civils 

Accounting System for sico civils repo
## Installation

### Clone repository

```
git clone https://github.com/Fraganya/sico-civils.git
```

### Install composer dependencies

Change into project directory and install composer dependencies

```
composer install
```

### Create .env file

Copy the <b>.env.example</b> file to <b>.env<b>

This can easily be achieved by running

```
cp .env.example .env
```

### Set Application key

Set the application key by running

```
php artisan key:generate
```

### Other Configs

To reflect your running environment, You can update the env parameters such as

#### Database

- Database name
- Database username
- Database password
- Database Port
- Database host

### Running Migrations

create the database tables by running the migrations

```
php artisan migrate
```
