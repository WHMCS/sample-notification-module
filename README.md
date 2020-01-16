# WHMCS Sample Notification Module #

## Summary ##

Notification Providers define how WHMCS communicates with and transmits
notifications configured using the Notifications feature.

Notifications in WHMCS are configured using rules. Each rule consists of an
event which triggers it, conditions required for it to trigger, and notification
settings which define the parameters used to send the notification.

You can learn more about the Notifications feature at
https://docs.whmcs.com/Notifications

For more information related to building a Notification Module, please refer to
the Developers documentation at:
https://developers.whmcs.com/notification-providers/

## Recommended Module Content ##

The recommended structure of a notification module is as follows.

```
 modules/notifications/your-module-name/
  | your-module-name.php
  | logo.png
  | whmcs.json
```

## Minimum Requirements ##

For the latest WHMCS minimum system requirements, please refer to
https://docs.whmcs.com/System_Requirements

We recommend your module follows the same minimum requirements wherever
possible.

## Useful Resources
* [Developer Resources](https://developers.whmcs.com/)
* [Hook Documentation](https://developers.whmcs.com/hooks/)
* [API Documentation](https://developers.whmcs.com/api/)

[WHMCS Limited](https://www.whmcs.com)
