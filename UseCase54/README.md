# UseCase54 — Kōjō + Symfony 5

Demonstrates that Kōjō works with Symfony 5.4 using the `hotfix-symfony-upgrade`
branch of the kojo package, which widens the Symfony constraint from `4.4.*` to
`4.4.*|^5.4|^6`.

The worker runs every minute, logs the installed `symfony/dependency-injection`
version, and completes successfully.

## Requirements

- PostgreSQL database (configured via env vars below)
- Redis
- PHP 7.2+

## Setup

Run all commands from the `UseCase54/` directory inside the KojoFitness Docker
container (or with matching env vars on the host).

```bash
# 1. Install dependencies (creates composer.lock pinned to Symfony 5.4)
DATABASE_NAME=kojo_fitness_54 composer install

# 2. Create the Kōjō schema
DATABASE_NAME=kojo_fitness_54 ./vendor/bin/kojo db:setup:install $PWD/src/V1/Environment/

# 3. Register the job type
DATABASE_NAME=kojo_fitness_54 php bin/setup-worker.php
```

## Running

```bash
DATABASE_NAME=kojo_fitness_54 ./vendor/bin/kojo process:pool:server:start $PWD/src/V1/Environment/
```

The pool picks up `kojofitness_uc54_symfony5` jobs (cron `* * * * *`, scheduleLimit 1)
and logs:

```
UC54: Kojo running successfully with Symfony DI v5.4.x (PHP x.x.x)
```

## Isolation note

UseCase54 and UseCase55 share the same PostgreSQL and Redis instances but use
separate databases (`kojo_fitness_54` / `kojo_fitness_55`). Each pool must be
started with the matching `DATABASE_NAME` override so it only processes its own
job type. Running both pools against a single shared database causes cross-
contamination: each pool will attempt to load the other use case's worker class,
which is not on its autoloader, and panic those jobs.

## What the worker does

`src/V1/Worker.php` reads the installed `symfony/dependency-injection` version via
`\Composer\InstalledVersions`, writes it to the Kōjō logger, then calls
`requestCompleteSuccess()` + `applyRequest()` to mark the job complete.

## Key learnings from implementation

- The path repository in `composer.json` points to `/kojo` (absolute Docker path).
  The kojo repo is mounted at `/kojo` via the volume added to `docker-compose.yml`.
  `symlink: false` is required — with `symlink: true`, PHP's `__DIR__` in
  `vendor/bin/kojo` resolves to the real path `/kojo/bin`, breaking the relative
  `../../../../vendor/autoload.php` load.
- Workers must call both `requestCompleteSuccess()` and `applyRequest()`. The
  Foreman checks `isRequestApplied()` after the worker returns and panics the job
  if it is false. `requestCompleteSuccess()` only records the intent; `applyRequest()`
  persists it to the database and sets the applied flag.
