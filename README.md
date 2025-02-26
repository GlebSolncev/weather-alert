# Weather Alert System

## Overview
This project provides weather condition notifications, specifically for ultraviolet (UV) index and precipitation levels. Notifications are sent via email and push notifications.

## Tech Stack
- Laravel 11 (Jetstream + Livewire)
- MySQL
- Redis
- Dockerized environment with Makefile

## Installation & Setup
To set up the project, run:

```sh
make setup
```

Once the setup is complete and all necessary files are available, you need to configure the `.env` file.

### Environment Configuration
You must provide integration details for:
- **SMTP settings** (e.g., Gmail credentials)
- **Weather API keys**
- **VAPID keys** (for push notifications)

#### SMTP Configuration
To configure SMTP, obtain an app password from your email provider’s security settings (for Gmail, this is found under two-factor authentication settings).

#### Weather API Key
You can obtain a Weather API key by either:
1. Contacting the project developer.
2. Registering an account on the Weather API provider's website to receive a free key.

#### VAPID Keys
Generate VAPID keys by running:

```sh
php artisan webpush:vapid
```

Then, add the generated keys to the `.env` file if empty.

## Running the Application
Once the setup is complete, visit:

```
http://localhost
```

You will be redirected to the login page. If you do not have an account, click **Create Account**, fill in the registration form, and submit it. Upon registration, you will be taken to the main page.

### Configuring Notifications
Initially, the main page will be empty. To configure alerts:
1. Click on your **name** in the top-right menu and select **Profile**.
2. Add a **country** and **city**.
3. Set your maximum thresholds for UV index and precipitation levels. If the real-time values exceed your set thresholds, you will receive a notification.
4. Click **Save** (or press **Enter**). A success or error message will appear.

#### Troubleshooting
- If you encounter an error, try changing the city name as it may be incorrectly formatted.
- Ensure values are formatted as `0.00`. Typical values range from `0.0005` to `5.45`.

## Data Synchronization & Notifications
- Notifications are sent every **15 minutes**.
- If a city is new (not previously stored in the system), data synchronization starts immediately.

## Dashboard & Graphs
After adding a city, return to the main page to view:
- A **graph** displaying UV index and precipitation levels.
- **Intervals on the right** and **latest values on the left**.
- Graphs update automatically every **15 minutes**.

Enjoy using the Weather Alert System!

