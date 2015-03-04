<p>
<a href="mailto://joel@kumuwai.com"><img src="http://img.shields.io/badge/author-joel-blue.svg" alt="Author"></a>
<a href="https://github.com/kumuwai/date-range"><img src="http://img.shields.io/badge/source-kumuwai%2Fdate--range-blue.svg" alt="Source Code"></a>
<a href="LICENSE.md"><img src="https://img.shields.io/badge/license-MIT-brightgreen.svg" alt="Software License"></a>
<br>
<a href="https://travis-ci.org/kumuwai/date-range"><img src="https://img.shields.io/travis/kumuwai/date-range/master.svg" alt="Build Status"></a>
<a href='https://coveralls.io/r/kumuwai/date-range'><img src='https://coveralls.io/repos/kumuwai/date-range/badge.svg' alt='Coverage Status' /></a>
<a href="https://scrutinizer-ci.com/g/kumuwai/date-range"><img src="https://img.shields.io/scrutinizer/g/kumuwai/date-range.svg" alt="Quality Score"></a>
</p>


FormattedCarbon
===============
The FormattedCarbon class decorates Carbon objects with user-defined styles. Each style to use for a project is specified in the configuration file. These are included by default:

    Style Name    PHP Format        Example
    -------------+-----------------+--------------------------
    default       n/j/Y             3/3/2015
    title         l, F j, Y         Tuesday, March 3, 2015
    long          D, M j, Y g:ia    Tue, Mar 3, 2015 1:14pm
    datetime      n/j/Y g:ia        3/3/2015 1:14pm
    time          g:ia              1:14pm
    short         n/j/Y             3/3/2015
    tiny          n/j/y             3/3/15
    pad           m/d/y             03/03/15
    padded        m/d/Y             03/03/2015
    month_name    F                 March
    month_year    F Y               March 2015
    day_detail    l (n/j/Y)         Tuesday (3/3/2015)
    day_only      n/j               3/3
    sql           Y-m-d             2015-03-03
    full          Y-m-d H:i:s       2015-03-03 13:14:00
    url           Y-m-d             2015-03-03
    timestamp     U                 1425424440


To use the class,

    $date = new FormattedCarbon($time, $tz, $config);

    where:

        $time is any readable time value. Possibilities are:
            timestamp
            string ("next Monday", "tuesday 1:12pm", "1/1/2015 4:00pm", etc.)
            A DateTime object
            A Carbon object
            Another FormattedCarbon object
            FormattedCarbon::NONE; eg, a date is not applicable for this field
            Null (for the current date/time)
        $tz is a timezone (or Null for the default timezone)
        $config is a configuration object that can return styles from a configuration file. (Null for default)

When you create a FormattedCarbon object, it can be used just like a Carbon object. You can do date comparisons, add / subtract dates, and so on. When you want to output data:

    $date->style('style');  // return a formatted string (based on format defined for the style)
    $date->style            // another way of doing the same thing.

It will also use the above formats to output Carbon strings, so each of these are functionally equivalent:

    $date->getCarbon()->toAtomString();     // will always be Carbon value
    $date->toAtomString();                  // will always be Carbon value
    $date->style('atom');                   // can be overridden in config file
    $date->atom;                            // can be overridden in config file

Please note that any custom-defined styles in the configuration file will override the default Carbon values. Carbon specifies these string output functions:

    Style          Carbon Method
    --------------+------------------------
    date           toDateString()
    formatteddate  toFormattedDateString()
    time           toTimeString()
    datetime       toDateTimeString()
    daydatetime    toDayDateTimeString()
    atom           toAtomString()
    cookie         toCookieString()
    iso8601        toIso8601String()
    rfc822         toRfc822String()
    rfc850         toRfc850String()
    rfc1036        toRfc1036String()
    rfc1123        toRfc1123String()
    rfc2822        toRfc2822String()
    rfc3339        toRfc3339String()
    rss            toRssString()
    w3c            toW3cString()

A sample configuration file is included. The format for this configuration file is as follows:

    'styles' => array(
        'style_name' => 'php date format string',
        'default' => 'n/j/Y',
        'title' => 'l, F j, Y',
        'long' => 'D, M j, Y g:ia',
        ...
    ),
    'none' => array( ... ),             // TODO: style handling for non-applicable dates
    'range' => array( ... ),            // TODO: style handling for date ranges
    'range-single' => array( ... ),     // TODO: style handling for same-time date ranges
    'calculations' => array( ... ),     // TODO: returning calculated values

The package also includes a sample index.php file that will show the results of the FormattedCarbon class for any given date value.


Installation
------------
Install the package via Composer. Edit your composer.json file to require kumuwai/date-range.

    "require": {
        "kumuwai/date-range": "dev-master"
    }

I have not put this on packagist, yet, so you'll also need to define where to get it:

    "repositories": [
        {
            "type": "vcs",
            "url":  "https://github.com/kumuwai/date-range"
        },

Next, update Composer from the terminal:

    composer update


TODO
----
* Build the actual DateRange class to handle ranges of formatted dates
* Better handling of non-applicable dates

