DateRange
=========
[![Build Status](https://img.shields.io/travis/kumuwai/date-range/master.svg)](https://travis-ci.org/kumuwai/date-range)
[![Coverage Status](https://coveralls.io/repos/kumuwai/date-range/badge.png?branch=master)](https://coveralls.io/r/kumuwai/date-range)
[![Quality Score](https://img.shields.io/scrutinizer/g/kumuwai/date-range.svg)](https://scrutinizer-ci.com/g/kumuwai/date-range)
[![License](https://img.shields.io/badge/license-MIT-blue.svg)](LICENSE.md)


DateRange is a class to help you conveniently format and use Carbon date ranges. For example:

```php
$dates = new DateRange('today', 'tomorrow');

echo $dates->short;  // 3/7/2015 – 3/8/2015
echo $dates->title;  // From Saturday, March 7, 2015 to Sunday, March 8, 2015
echo $dates->url;  // start=2015-03-07T000000-1000&end=2015-03-08T000000-1000
```


Documentation
--------------
You will find documentation [in the wiki](https://github.com/kumuwai/date-range/wiki).

