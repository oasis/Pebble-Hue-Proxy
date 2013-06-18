Pebble Hue Proxy 
================

This is a simple proof of concept PHP script to relay HTTP Requests from a Pebble to your Hue Bridge using the unofficial Pebble iOS companion app [Smartwatch+](http://smartwatchplusapp.appspot.com‎)

Getting Started
---------------

You will need a webserver running PHP on the same network as your Hue bridge, or somewhere that can connect to the Hue bridge.
You may need to set up port forwarding on your router if the server is on a different network.

Edit config.php to specify your bridge's IP address and a select a username you wish to connect as.

Once your files are uploaded to the webserver, press the button on your Hue bridge and run register.php to authenticate the proxy.

If all goes well, you should see a notice that the proxy is now registered with the bridge.

You can now send HTTP requests to on-off.php and dimmer.php to toggle the lights and brightness.

Future Developments
-------------------

Currently Smartwatch+ only supports GET requests, but the developer has indicated possible future support for PUT and POST, potentially allowing the Pebble to talk directly with the bridge without a relay. However, a relay may still be useful to support single button toggles based on current state, such as the ON/OFF toggle and Dim/Bright toggle included here. Upcoming support for multiple HTTP Request slots in Smartwatch+ also opens up many other posibilities, such as a sliding brightness toggle, and support for individual lights. The included PHP Hue library (forked from code by airox) supports way more than toggling on/off and brightness. Possible applications include changing colors or rotating scenes. 
