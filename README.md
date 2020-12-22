# PushCow SDK
PushCow SDK for PHP.

## Installation (Laravel)
Add the following to ```composer.json```:
```php
"repositories": [
    {
        "type": "vcs",
        "url": "git@bitbucket.org:innoractivehackers/pushcow-sdk.git"
    }
]
```
Then, run ```composer require innoractive/pushcow-sdk```.
After the installation completed, add ```Innoractive\PushCow\PushNotificationServiceProvider::class``` to the autoloaded service providers array in ```config/app.php```:
```php
'providers' => [
    // ...
    Innoractive\PushCow\PushNotificationServiceProvider::class,
],
```
Publish the config file with the command: ```php artisan vendor:publish --provider="Innoractive\PushCow\PushNotificationServiceProvider" --tag="config"```.
A config file will be created at ```config/push-notification.php```. Update the config and the setup is done!

## Usage
```php
use Innoractive\PushCow\PushCow;

// (Not required for Laravel) Initialise PushCow service.
PushCow::init('PUSHCOW_BASE_URL', 'PUSHCOW_APP_TOKEN');

// Get the PushCow service status.
PushCow::status();

// To register or update an existing device.
PushCow::registerDevice($deviceId, $token, $userId);

// To unregister an existing device.
PushCow::unregisterDevice($deviceId, $token, $userId);

// Create a new message.
PushCow::createMessage($recipients, $notification, $data);
```
