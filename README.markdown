RadarScope
===========

RadarScope is a MyBlogLog API library for lifestreaming applications.

RadarScope leverages the aggregated social network information MyBlogLog
collects and provides it in a consistent format for embedding into other
applications. It was created to provide a lifestream for [my website][].

RadarScope is very simple right now. I plan on adding more robust
functionality, such as aggregating similar entries together, handling the
conversion to a HTML list, supporting customization per-service, and someday
supporting more than one lifestream backend service.

[My Website]: http://reidburke.com/


Usage
-----

Terminal:

    $ git clone git://github.com/reid/radarscope.git
    $ cd radarscope
    $ cat YOUR_YAHOO_WS_KEY >> MBLApplicationId.txt
    $ mkdir cache

sample.php:

    <?php

    require 'radarscope/MyBlogLog.php';

    $member_id = '2007071216445792';

    $mbl = new MyBlogLog($member_id);
    $data = $mbl->newWithMe();

    print_r($data);

Terminal:

    $ php sample.php

License
-------

RadarScope is provided under the BSD license. See LICENSE for more information.
