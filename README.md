# Icade Football app

## Requirements

- PHP ^7.4
- Composer ^2.0
- Symfony CLI ^4.26
- Node JS ^15.6
- Yarn ^1.22

## Initialization

### Installation

Install PHP vendors

```bash
composer install
```

Install front dependencies

```bash
yarn install
```

Create the `.env.local` file

```bash
cp .env .env.local
```

Then set the `API_KEY` environment variable with your personnal key



You can then run the project server

```bash
symfony server:start
```

And visit your app in your browser at `http://localhost:8000/` 