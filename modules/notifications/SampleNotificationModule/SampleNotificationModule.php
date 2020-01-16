<?php

namespace WHMCS\Module\Notification\SampleNotificationModule;

use WHMCS\Module\Notification\DescriptionTrait;
use WHMCS\Module\Contracts\NotificationModuleInterface;
use WHMCS\Notification\Contracts\NotificationInterface;

/**
 * Sample Notification Module
 *
 * All notification modules must implement NotificationModuleInterface
 */
class SampleNotificationModule implements NotificationModuleInterface
{
    use DescriptionTrait;

    /**
     * Constructor
     *
     * Any instance of a notification module should have the display name and
     * logo filename at the ready.  Therefore it is recommend to ensure these
     * values are set during object instantiation.
     *
     * The sample notification module utilizes the DescriptionTrait which
     * provides methods to fulfill this requirement.
     *
     * @see \WHMCS\Module\Notification\DescriptionTrait::setDisplayName()
     * @see \WHMCS\Module\Notification\DescriptionTrait::setLogoFileName()
     */
    public function __construct()
    {
        $this->setDisplayName('Sample Notification Module')
            ->setLogoFileName('logo.png');
    }

    /**
     * Settings required for module configuration
     *
     * The method should provide a description of common settings required
     * for the notification module to function.
     *
     * For example, if the module connects to a remote messaging service this
     * might be username and password or OAuth token fields required to
     * authenticate to that service.
     *
     * This is used to build a form in the UI.  The values submitted by the
     * admin based on the form will be validated prior to save.
     * @see testConnection()
     *
     * The return value should be an array structured like other WHMCS modules.
     * @link https://developers.whmcs.com/payment-gateways/configuration/
     *
     * @return array
     */
    public function settings()
    {
        return [
            'api_username' => [
                'FriendlyName' => 'API Username',
                'Type' => 'text',
                'Description' => 'Required username to authenticate with message service',
            ],
            'api_password' => [
                'FriendlyName' => 'API Password',
                'Type' => 'password',
                'Description' => 'Required password to authenticate with message service',
            ],
        ];
    }

    /**
     * Validate settings for notification module
     *
     * This method will be invoked prior to saving any settings via the UI.
     *
     * Leverage this method to verify authentication and/or authorization when
     * the notification service requires a remote connection.
     *
     * In the event of failure, throw an exception. The exception will be displayed
     * to the user.
     *
     * @param array $settings
     *
     * @throws \Exception
     */
    public function testConnection($settings)
    {
        // Check to ensure both api_username and api_password were provided
        if (empty($settings['api_username']) || empty($settings['api_password'])) {
            throw new \Exception('API Login Failed. Please check your credentials input and try again.');
        }

        // Perform API call here to validate the supplied API username and password.
        // Return an exception if the connection fails.
    }

    /**
     * The individual customisable settings for a notification.
     *
     * These settings are provided to the user whilst configuring individual notification rules.
     *
     * The "Type" of a setting can be text, password, yesno, dropdown, radio, textarea and dynamic.
     *
     * @see getDynamicField for how to obtain dynamic values
     *
     * @return array
     */
    public function notificationSettings()
    {
        return [
            'botname' => [
                'FriendlyName' => 'Bot Name',
                'Type' => 'text',
                'Description' => 'Define the name of your notification bot.',
                'Required' => true,
            ],
            'channel' => [
                'FriendlyName' => 'Channel',
                'Type' => 'dynamic',
                'Description' => 'Select the desired channel for notification delivery.',
                'Required' => true,
            ],
        ];
    }

    /**
     * The option values available for a 'dynamic' Type notification setting
     *
     * @see notificationSettings()
     *
     * @param string $fieldName Notification setting field name
     * @param array $settings Settings for the module
     *
     * @return array
     */
    public function getDynamicField($fieldName, $settings)
    {
        if ($fieldName == 'channel') {
            return [
                'values' => [
                    [
                        'id'          => 1,
                        'name'        => 'Tech Support',
                        'description' => 'Channel ID',
                    ],
                    [
                        'id'          => 2,
                        'name'        => 'Customer Service',
                        'description' => 'Channel ID',
                    ],
                ],
            ];
        }

        return [];
    }

    /**
     * Deliver notification
     *
     * This method is invoked when rule criteria are met.
     *
     * In this method, you should craft an appropriately formatted message and
     * transmit it to the messaging service.
     *
     * WHMCS provides a getAttributes method via $notification here. This method returns a NotificationAttributeInterface
     * object which allows you to obtain key data for the Notification.
     *
     * @param NotificationInterface $notification A notification to send
     * @param array $moduleSettings Configured settings of the notification module
     * @param array $notificationSettings Configured notification settings set by the triggered rule
     *
     * @throws \Exception on error of sending notification
     *
     * @see https://classdocs.whmcs.com/7.8/WHMCS/Notification/Contracts/NotificationInterface.html
     * @see https://classdocs.whmcs.com/7.8/WHMCS/Notification/Contracts/NotificationAttributeInterface.html
     */
    public function sendNotification(NotificationInterface $notification, $moduleSettings, $notificationSettings)
    {
        if (!$notificationSettings['channel']) {
            // Abort the Notification.
            throw new \Exception('No channel selected for notification delivery.');
        }

        $notificationData = [
            'channel'                 => $notificationSettings['channel'],
            'notification_title'      => $notification->getTitle(),
            'notification_url'        => $notification->getUrl(),
            'notification_message'    => $notification->getMessage(),
            'notification_attributes' => [],
        ];

        foreach ($notification->getAttributes() as $attribute) {
            $notificationData['notification_attributes'][] = [
                'label' => $attribute->getLabel(),
                'value' => $attribute->getValue(),
                'url'   => $attribute->getUrl(),
                'style' => $attribute->getStyle(),
                'icon'  => $attribute->getIcon(),
            ];
        }

        // Perform API call to your notification provider.

        if (array_key_exists('error', $response)) {
            // The API returned an error. Perform an action and abort the Notification.
            throw new \Exception('Notification delivery failed.');
        }
    }
}
