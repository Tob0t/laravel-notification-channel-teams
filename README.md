# Microsoft Teams Notifications Channel for Laravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/laravel-notification-channels/teams.svg?style=flat-square)](https://packagist.org/packages/laravel-notification-channels/teams)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/laravel-notification-channels/teams/master.svg?style=flat-square)](https://travis-ci.org/laravel-notification-channels/teams)
[![StyleCI](https://styleci.io/repos/:style_ci_id/shield)](https://styleci.io/repos/:style_ci_id)
[![SensioLabsInsight](https://img.shields.io/sensiolabs/i/:sensio_labs_id.svg?style=flat-square)](https://insight.sensiolabs.com/projects/:sensio_labs_id)
[![Quality Score](https://img.shields.io/scrutinizer/g/laravel-notification-channels/teams.svg?style=flat-square)](https://scrutinizer-ci.com/g/laravel-notification-channels/teams)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/laravel-notification-channels/teams/master.svg?style=flat-square)](https://scrutinizer-ci.com/g/laravel-notification-channels/teams/?branch=master)
[![Total Downloads](https://img.shields.io/packagist/dt/laravel-notification-channels/teams.svg?style=flat-square)](https://packagist.org/packages/laravel-notification-channels/teams)

This package makes it easy to send notifications using [Microsoft Teams](https://products.office.com/en-US/microsoft-teams/group-chat-software) with Laravel 5.5+, 6.x and 7.x

```php
return MicrosoftTeamsMessage::create()
    ->to(config('services.teams.sales_url'))
    ->type('success')
    ->title('Subscription Created')
    ->content('Yey, you got a **new subscription**. Maybe you want to contact him if he needs any support?')
    ->button('Check User', 'https://foo.bar/users/123');
```
## Contents

- [Installation](#installation)
	- [Setting up the Connector](#setting-up-the-Connector)
	- [Setting up the MicrosoftTeams service](#setting-up-the-MicrosoftTeams-service)
- [Usage](#usage)
	- [Available Message methods](#available-message-methods)
- [Changelog](#changelog)
- [Testing](#testing)
- [Security](#security)
- [Contributing](#contributing)
- [Credits](#credits)
- [License](#license)


## Installation

You can install the package via composer:

``` bash
composer require tob0t/laravel-notification-channel-teams
```

### Setting up the Connector

Please check out [this](https://docs.microsoft.com/en-gb/microsoftteams/platform/webhooks-and-connectors/how-to/add-incoming-webhook#add-an-incoming-webhook-to-a-teams-channel) for setting up and adding a webhook connector to your Team's channel. Basic Markdown is supported, please also check out the [message card reference article](https://docs.microsoft.com/en-us/outlook/actionable-messages/message-card-reference#httppost-action) which goes in more detail about the do's and don'ts.

### Setting up the MicrosoftTeams service

Then, configure your webhook url:

Add the following code to your `config/services.php`:

```php
// config/services.php
...
'teams' => [
    'webhook_url' => env('TEAMS_WEBHOOK_URL'),
],
...
```

You can also add multiple webhooks if you have multiple teams or channels, it's up to you.

```php
// config/services.php
...
'teams' => [
    'sales_url' => env('TEAMS_SALES_WEBHOOK_URL'),
    'dev_url' => env('TEAMS_DEV_WEBHOOK_URL'),
],
...
```
## Usage

Now you can use the channel in your `via()` method inside the notification:

```php
use Illuminate\Notifications\Notification;
use NotificationChannels\MicrosoftTeams\MicrosoftTeamsChannel;
use NotificationChannels\MicrosoftTeams\MicrosoftTeamsMessage;

class SubscriptionCreated extends Notification
{
    public function via($notifiable)
    {
        return [MicrosoftTeamsChannel::class];
    }

    public function toMicrosoftTeams($notifiable)
    {
        return MicrosoftTeamsMessage::create()
            ->to(config('services.teams.sales_url'))
            ->type('success')
            ->title('Subscription Created')
            ->content('Yey, you got a **new subscription**. Maybe you want to contact him if he needs any support?')
            ->button('Check User', 'https://foo.bar/users/123');
    }
}
```

Instead of adding the `to($url)` method for the recipient you can also add the `routeNotificationForMicrosoftTeams` method inside your Notifiable model. This method needs to return the webhook url.

```php
public function routeNotificationForMicrosoftTeams(Notification $notification)
{
    return config('services.teams.sales_url')
}
```


### Available Message methods

- `to(string $webhookUrl)`: Recipient's webhook url.
- `title(string $title)`: Title of the message.
- `summary(string $summary)`: Summary of the message.
- `type(string $type)`: Type which is used as theme color (any valid hex code or one of: primary|secondary|accent|error|info|success|warning).
- `content(string $content)`: Content of the message (Markdown supported).
- `button(string $text, string $url = '', $type = 'OpenUri', array $params = [])`: Text and url of a button. For more Infos about different types check out [this link](https://docs.microsoft.com/en-us/outlook/actionable-messages/message-card-reference#actions).
- `options(array $options, $sectionId = null)`: Add additional options to pass to message payload object.

#### Sections
It is possible to define one or many sections inside a message card. The following methods can be used within a section
- `addStartGroupToSection($sectionId = 'standard_section')`: Add a startGroup property which marks the start of a logical group of information.
- `activity(string $activityImage = '', string $activityTitle = '', string $activitySubtitle = '', string $activityText = '', $sectionId = 'standard_section')`: Add an activity to a section.
- `fact(string $name, string $value, $sectionId = 'standard_section')`: Add a fact to a section (Supports Markdown).
- `image(string $imageUri, string $title = '', $sectionId = 'standard_section')`: Add an image to a section.
- `heroImage(string $imageUri, string $title = '', $sectionId = 'standard_section')`: Add a hero image to a section.

Additionally the title, content and button can be also added to a section through the optional `params` value:
- `title(string $title, array $params = ['section' => 'my-section'])`: Title of the message and add it to `my-section`.
- `content(string $content, array $params = ['section' => 'my-section'])`: Content of the message and add it to `my-section` (Markdown supported).
- `button(string $text, string $url = '', $type = 'OpenUri', array $params = ['section' => 'my-section'])`: Text and url of a button and add it to `my-section`.

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Security

If you discover any security related issues, please email tobias.madner@gmx.at instead of using the issue tracker.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [Tobias Madner](https://github.com/Tob0t)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
