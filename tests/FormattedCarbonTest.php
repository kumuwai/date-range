<?php

use Kumuwai\DateRange\FormattedCarbon;
use Carbon\Carbon;


class FormattedCarbonTest extends PHPUnit_Framework_TestCase
{
	private $now;

	public function setUp()
	{
		$this->now = Carbon::parse('2015-02-22 12:48:34pm');
		Carbon::setTestNow($this->now);
	}

	public function tearDown()
	{
		Mockery::close();
	}

	public function testDefaultToNow()
	{
		$test = new FormattedCarbon;
		$this->assertEquals((string)$this->now, $test->toSql());
	}

	public function testCanCreateWithString()
	{
		$test = new FormattedCarbon('2/1/2015');
		$this->assertEquals('2015-02-01 00:00:00', $test->toSql());
	}

	public function testCanCreateWithTimestamp()
	{
		$test = new FormattedCarbon(1422857565);
		$this->assertEquals('2015-02-01 20:12:45', $test->toSql());
	}

	public function testCanCreateWithDateTimeObject()
	{
		$test = new FormattedCarbon(new DateTime('2/1/2015 20:12:45'));
		$this->assertEquals('2015-02-01 20:12:45', $test->toSql());
	}

	public function testCanExecuteCarbonMethod()
	{
		$test = new FormattedCarbon();
		$this->assertNotNull($test->getTimezone());
		$this->assertInstanceOf('DateTimeZone', $test->getTimezone());
	}

	/**
	 * @dataProvider getTimezones
	 */
	public function testCanCreateWithDateTimeObjectInTimezone($timezone)
	{
		$date = new DateTime('2/1/2015 20:12:45', new DateTimeZone($timezone));
		$test = new FormattedCarbon($date);
		$this->assertEquals('2015-02-01 20:12:45', $test->toSql());
		$this->assertEquals($timezone, $test->getTimezone()->getName());
	}

	public function getTimezones()
	{
		return array(['HST'],['UTC'],['Pacific/Honolulu']);
	}

	public function testCanCreateWithCarbonObject()
	{
		$date = new Carbon($this->now);
		$test = new FormattedCarbon($date);
		$this->assertEquals('2015-02-22 12:48:34', $test->toSql());
		$this->assertEquals(False, $test->isNone());
	}

	public function testCanCreateWithNAConstant()
	{
		$test = new FormattedCarbon(FormattedCarbon::NONE);
		$this->assertEquals(True, $test->isNone());
	}

	public function testCanCreateWithNAMethod()
	{
		$test = new FormattedCarbon((new FormattedCarbon)->none());
		$this->assertEquals(True, $test->isNone());
	}

	public function testCanCreateWithOtherFormattedCarbonObject()
	{
		$test = new FormattedCarbon(new FormattedCarbon);
		$this->assertEquals('2015-02-22 12:48:34', $test->toSql());
	}

	/**
	 * @dataProvider getCarbonConstructors
	 */
	public function testCanCreateWithCarbonConstructor($method, $params, $expected)
	{
		$test = call_user_func_array([new FormattedCarbon, $method], $params);
		$this->assertInstanceOf('Kumuwai\DateRange\FormattedCarbon', $test);
		$this->assertEquals($expected, $test->toSql());
	}

	public function getCarbonConstructors()
	{
		$result = '2015-02-22 12:48:34';

		return array(
			['instance', [new DateTime('2/22/2015 12:48:34pm')], $result],
   			['parse', ['02/22/2015 12:48:34'], $result],
		    ['now', [], $result],
		    ['today', [], '2015-02-22 00:00:00'],
		    ['tomorrow', [], '2015-02-23 00:00:00'],
			['yesterday', [], '2015-02-21 00:00:00'],
            ['create', [2015, 2, 22, 12, 48, 34], $result],
            ['createFromFormat', ['YmdHis', '20150222124834'], $result],
            ['createFromFormat', ['Y-m-d-H-i-s', '2015-02-22-12-48-34'], $result],
            ['createFromTimestamp', [1422857565], '2015-02-01 20:12:45'],
		    // ['maxValue', [], Carbon::maxValue()],		// too big to be valid
		    // ['minValue', [], Carbon::minValue()],		// too big to be valid??
            // ['createFromDate', [2015, 2, 22], '2015-02-22 00:00:00'], 	// includes current TIME
            // ['createFromTime', [12, 48, 34], '12:48:34'],				// includes current DATE
            // ['createFromTimestampUTC', [1422857565], '2015-02-01 20:12:45'],  // Converts based on user tz
		);
	}

	public function testCanCopy()
	{
		$test = (new FormattedCarbon)->copy();
		$this->assertEquals('2015-02-22 12:48:34', $test->toSql());
	}

	/**
	 * @dataProvider getCarbonFieldData
	 */
	public function testCanGetCarbonFields($field, $expected)
	{
		$this->assertEquals($expected, (new FormattedCarbon)->$field);
	}

	public function getCarbonFieldData()
	{
		return array(
			['month', 2],
			['year', 2015],
			['timestamp', 1424645314],
		);
	}

	public function testCanSetCarbonField()
	{
		$test = new FormattedCarbon;
		$test->month = 4;
		$this->assertEquals('2015-04-22 12:48:34', $test->toSql());
	}

	public function testCanGetDateString()
	{
		$test = new FormattedCarbon;
		$this->assertEquals('2015-02-22 12:48:34', $test->toDateTimeString());
	}

	public function testCanCompareTwoObjects()
	{
		$test1 = new FormattedCarbon;
		$test2 = new FormattedCarbon;
		$this->assertTrue($test1->eq($test2));
	}

	public function testCanCompareTwoDifferentObjects()
	{
		$test1 = new FormattedCarbon;
		$test2 = $test1->copy()->addDays(2);
		$this->assertTrue($test1->lt($test2));
		$this->assertEquals(2, $test1->diffInDays($test2));
		$this->assertEquals(48, $test1->diffInHours($test2));
		$this->assertEquals('2 days before', $test1->diffForHumans($test2));
	}

	public function testCanReturnListOfStyles()
	{
		$config = Mockery::mock('Config');
		$config->shouldReceive('get')->once()->andReturn(['x']);
		$test = new FormattedCarbon(Null, Null, $config);
		$this->assertEquals(['x'], $test->getStyles());
	}

	public function testCanGetFormattedValues()
	{
		$config = Mockery::mock('Config');
		$config->shouldReceive('get')->times(2)
			->with('date-range::styles.foo')
			->andReturn('Y-m');
		$test = new FormattedCarbon(Null, Null, $config);
		$this->assertEquals('Y-m', $test->getStyle('foo'));
		$this->assertEquals('2015-02', $test->foo);
	}

	public function testCanUseDefaultStyles()
	{
		$test = new FormattedCarbon;
		$this->assertNotNull($test->getStyles());
		$this->assertNotEmpty($test->getStyles());
		$this->assertEquals('n/j/Y', $test->getStyle('short'));
		$this->assertEquals('2/22/2015', $test->style('short'));
		$this->assertEquals('2/22/2015', $test->short);
	}

	// /**
	//  * @expectedException InvalidArgumentException
	//  */
	// public function testThrowExceptionForNonExistantGetter()
	// {
	// 	$config = Mockery::mock('Config')->shouldReceive('get')->andReturn(Null)->getMock();
	// 	$test = new FormattedCarbon(Null, Null, $config);
	// 	$a = $test->some_random_non_existent_property_name;
	// }

	public function testReturnEmptyStringForNonExistantGetter()
	{
		$config = Mockery::mock('Config')->shouldReceive('get')->andReturn(Null)->getMock();
		$test = new FormattedCarbon(Null, Null, $config);
		$this->assertEquals('',$test->some_random_non_existent_property_name);
	}

	public function testReturnNothingForNonApplicableResults()
	{
		$test = new FormattedCarbon(FormattedCarbon::NONE);
		$this->assertEquals('', $test->day);
		$this->assertEquals('', $test->isWeekday());
	}

	public function testCanNotCompareNonApplicableObject()
	{
		$test1 = new FormattedCarbon;
		$test2 = new FormattedCarbon(FormattedCarbon::NONE);
		$this->assertEquals('', $test1->lt($test2));
		$this->assertEquals('', $test2->lt($test1));
		$this->assertEquals('', $test1->diffInDays($test2));
		$this->assertEquals('', $test1->diffInHours($test2));
		$this->assertEquals('', $test1->diffForHumans($test2));
	}

	/**
	 * @dataProvider getCarbonStringFormats
	 */
	public function testCanReturnCarbonFormattedStrings($carbon, $style)
	{
		$test = new FormattedCarbon;
		$expected = new Carbon($this->now);
		$this->assertEquals($expected->$carbon(), $test->style($style));
		$this->assertEquals($expected->$carbon(), $test->$style);
	}

	public function getCarbonStringFormats()
	{
		return array(
			['toDateString', 'date'],
			['toFormattedDateString', 'formatteddate'],
			// ['toTimeString', 'time'],				// overridden in default config
			// ['toDateTimeString', 'datetime'],		// overridden in default config
			['toDayDateTimeString', 'daydatetime'],
			['toAtomString', 'atom'],
			['toCookieString', 'cookie'],
			['toIso8601String', 'iso8601'],
			['toRfc822String', 'rfc822'],
			['toRfc850String', 'rfc850'],
			['toRfc1036String', 'rfc1036'],
			['toRfc1123String', 'rfc1123'],
			['toRfc2822String', 'rfc2822'],
			['toRfc3339String', 'rfc3339'],
			['toRssString', 'rss'],
			['toW3cString', 'w3c'],
		);
	}

	public function testEquivalentObjects()
	{
		$test = new FormattedCarbon;
		$this->assertEquals($test->getCarbon()->toAtomString(), $test->toAtomString());
		$this->assertEquals($test->getCarbon()->toAtomString(), $test->style('atom'));
		$this->assertEquals($test->getCarbon()->toAtomString(), $test->atom);
	}

	// public function testReturnDefaultIfStyleNotFound()
	// {

	// }

}




