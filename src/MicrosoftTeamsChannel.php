<?php

namespace NotificationChannels\MicrosoftTeams;

use Illuminate\Notifications\Notification;
use NotificationChannels\MicrosoftTeams\Exceptions\CouldNotSendNotification;

class MicrosoftTeamsChannel
{
    /**
     * @var MicrosoftTeams
     */
    protected $microsoftTeams;

    /**
     * Channel constructor.
     *
     * @param MicrosoftTeams $microsoftTeams
     */
    public function __construct(MicrosoftTeams $microsoftTeams)
    {
        $this->microsoftTeams = $microsoftTeams;
    }

    /**
     * Send the given notification.
     *
     * @param mixed $notifiable
     * @param Notification $notification
     *
     * @throws CouldNotSendNotification
     */
    public function send($notifiable, Notification $notification)
    {
        $message = $notification->toMicrosoftTeams($notifiable);

        // if the recipient is not defined check if from the notifiable object
        if ($message->toNotGiven()) {
            if (! $to = $notifiable->routeNotificationFor('microsoftTeams')) {
                throw CouldNotSendNotification::microsoftTeamsWebhookUrlMissing();
            }

            $message->to($to);
        }

        $response = $this->microsoftTeams->send($message->getWebhookUrl(), $message->toArray());

        return $response;
    }
}
