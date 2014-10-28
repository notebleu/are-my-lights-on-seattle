are-my-lights-on-seattle
========================

This is a small project conceived on a windy night in Seattle

In local.php, set the latitude and longitude, along with an email address (most cell phone carriers also provide email-to-text options) of any locations you want to test, then set a job on the server where this is installed to run index.php at your desired frequency.

I've set up a very simple flat file storage system (models/storage.class.php), but feel free to branch this and work in other storage drivers.

Likewise, the Messeger is just PHP's mail function (models/messeger.class.php), if you want to get fancy with this, by all means.

The location algorythm is based on AssemblySys dataServices' concept here: http://assemblysys.com/php-point-in-polygon-algorithm/

All data comes from City of Seattle's service outage API: http://www.seattle.gov/light/sysstat/

Please note this really only works for Seattle and the nearby surrounding area, but with a little elbow grease, I'm sure the concept could be applied to any system with a similar API.

Thanks for checking it out!

More information at https://notebleu.com/software/are-my-lights-on-seattle