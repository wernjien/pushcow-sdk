# PushCow SDK
PHP SDK for the PushCow API. 

## Installation
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

## Usage
```php
use Innoractive\PushCow\PushCow;

// Initialise PushCow service.
$pushcow = new PushCow($baseUrl, $appToken);

// Get the PushCow service status.
$pushcow->status();

// To register or update an existing device.
$pushcow->registerDevice($platform, $deviceId, $deviceToken, $userId);

// To unregister an existing device.
$pushcow->unregisterDevice($deviceId, $deviceToken, $userId);

// Create a new message.
$pushcow->createMessage($recipients, $notification, $data, $options);
```
