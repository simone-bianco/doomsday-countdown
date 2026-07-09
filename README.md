# AI Laravel Starter

Plug-and-play Laravel 13 quickstart with local Simone Bianco AI/UI packages copied into `packages/simone-bianco` so you can patch them inside this project.

## Setup

```bash
composer install
npm install
php artisan migrate
php artisan form-bridge:generate
php artisan typescript:transform
php artisan initialize-ai-project
npm run dev
```

Production build:

```bash
npm run build
```

Windows helpers:

- `start-dev.bat` starts Laravel, queue worker, and Vite.
- `start-ngrok-all.bat` builds assets, starts Laravel/queue, and opens ngrok.

## Included local packages

- `simone-bianco/laravel-form-bridge`
- `simone-bianco/laravel-key-rotator`
- `simone-bianco/laravel-patches`
- `simone-bianco/laravel-fill-variables`
- `@simone-bianco/vue-form-core`
- `@simone-bianco/vue-ui-components`
- `simone-bianco/vue-ui-components-testing`

## Runtime surfaces

- `/` public Vue home with i18next language switcher and agent debug panel.
- `POST /agent/demo` demo agent endpoint. It wraps a classic LarAgent class under `app/Ai/Agents` and uses `laravel-key-rotator` for OpenAI key selection/usage tracking.
- `/{AI_STARTER_BACKOFFICE_PATH}` authenticated backoffice. Default: `/backoffice`.
- `/test-components` from `vue-ui-components-testing` when the package routes are enabled.

## Initialize command

`php artisan initialize-ai-project` asks for:

- app name
- app URL
- backoffice path
- optional OpenAI key
- OpenAI model
- admin user name/email/password

It updates `.env` and `.env.example`, clears config, creates/updates the admin user, and registers the OpenAI key in key-rotator when provided.
