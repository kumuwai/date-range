<?php 

use Kumuwai\DateRange\DateRange;
use Carbon\Carbon;


class DateRangeTest extends PHPUnit_Framework_TestCase
{
	private $now;

	public function setUp()
	{
		$this->now = Carbon::parse('2015-02-22 2:48:34pm');
		Carbon::setTestNow($this->now);
	}

	public function tearDown()
	{
		Carbon::setTestNow();
	}

	public function testExists()
	{
		$test = new DateRange;
	}

	public function testDefaultToFormattedCarbonObjectSetToNow()
	{
		$test = new DateRange;
		$this->assertInstanceOf('Kumuwai\DateRange\FormattedCarbon', $test->start());
		$this->assertInstanceOf('Kumuwai\DateRange\FormattedCarbon', $test->end());
		$this->assertEquals($this->now, $test->start()->getCarbon());
		$this->assertEquals($this->now, $test->end()->getCarbon());
	}

	public function testEndDateDefaultsToStartDate()
	{
		$test = new DateRange('2/22/15 2:48:34pm');
		$this->assertEquals('2015-02-22 14:48:34', $test->start()->style('datetime'));
		$this->assertEquals('2015-02-22 14:48:34', $test->end()->style('datetime'));
	}

	public function testCanSetStartAndEndDatesSeparately()
	{
		$test = new DateRange('2/22/15 2:48:34pm', '3/1/15 2:22:15am');
		$this->assertEquals('2015-02-22 14:48:34', $test->start()->style('datetime'));
		$this->assertEquals('2015-03-01 02:22:15', $test->end()->style('datetime'));
	}

	public function testCanReturnSingleFormattedDate()
	{
		$test = new DateRange('2/22/15 2:48:34pm', Null, 'HST');
		$this->assertEquals('2/22/2015', $test->style('default'));		
		$this->assertEquals('date=2015-02-22T144834-1000', $test->style('url'));		
	}

	public function testWillReturnEmptyStringIfUnknownFormatRequested()
	{
		$test = new DateRange('2/22/15 2:48:34pm', Null, 'HST');
		$this->assertEquals('', $test->style('some_unknown_format'));		
	}

	public function testReturnDefaultStyleIfStringRequested()
	{
		$test = new DateRange('2/22/15 2:48:34pm', Null, 'HST');
		$this->assertEquals('2/22/2015', $test->style('default'));
		$this->assertEquals('2/22/2015', $test);
	}

	/**
	 * @dataProvider getDataFormats
	 */
	public function testCanShowDataInDesiredFormat($style, $expected)
	{
		$test = new DateRange('2/22/15 12:48:34pm', '3/1/15 2:22:15pm');
		$this->assertEquals($expected, $test->style($style));
		$this->assertEquals($expected, $test->$style);
	}

	public function getDataFormats()
	{
		return array(
			// Standard range values
			['default', '2/22/2015 &ndash; 3/1/2015'],
			['url', 'start=2015-02-22T124834-1000&end=2015-03-01T142215-1000'], 
			['title', 'From Sunday, February 22, 2015 to Sunday, March 1, 2015'], 

			// Alternatively formatted ranges
			['sql', '2015-02-22 &ndash; 2015-03-01'],
			['time', '12:48pm &ndash; 2:22pm'],
			['short', '2/22/2015 &ndash; 3/1/2015'],
			['tiny', '2/22/15 &ndash; 3/1/15'],
			['range_tiny', '2/22/15 &ndash; 3/1/15'],
			['atom', '2015-02-22T12:48:34-10:00 &ndash; 2015-03-01T14:22:15-10:00'],

			// Start and end values
			['start_sql', '2015-02-22'],
			['start', '2/22/2015'],
			['end_sql', '2015-03-01'],
			['end', '3/1/2015'],

			// Calculations
			['days', 7],
			['months', 0],
			['decimal', 12.8],   // a decimal representation of the start time
			['sql_range', ['2015-02-22','2015-03-01 23:59:59']],

			// Non-valid formats
			['something_not_found', ''],
			['something_else_that_doesnt_exist', ''],
		);
	}

	public function testCanGetHumanReadableFormats()
	{
		$test = new DateRange('2/22/15 12:48:34pm');
		$this->assertEquals('2 hours ago', $test->human);
		
		$test = new DateRange('2/20/15 12:48:34pm');
		$this->assertEquals('2 days ago', $test->human);

		$test = new DateRange('2/14/15 12:48:34pm');
		$this->assertEquals('2/14/2015 at 12:48pm', $test->human);
	}

}
