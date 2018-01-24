<?php

namespace Tests\Tlapnet\ReportMailing;

use Mockery;
use Mockery\MockInterface;
use Tlapnet\ReportMailing\Feed;
use Tlapnet\ReportMailing\MailConfig;
use Tlapnet\ReportMailing\ReportSender;
use Tlapnet\ReportMailing\ReportSenderJob;

final class ReportSenderJobTest extends MockeryTest
{

	/**
	 * @return void
	 */
	public function testIsDue()
	{
		$feed = new Feed(new MailConfig([]), '*/2 * * * *', []);

		/** @var ReportSender|MockInterface $reportSender */
		$reportSender = Mockery::mock(ReportSender::class);

		$job = new ReportSenderJob($feed, $reportSender);

		self::assertTrue($job->isDue(new \DateTime('2000-01-01 00:00')));
		self::assertFalse($job->isDue(new \DateTime('2000-01-01 00:01')));
		self::assertSame($feed, $job->getFeed());
	}

	/**
	 * @return void
	 */
	public function testRun()
	{
		$feed = new Feed(new MailConfig([]), '* * * * *', []);

		/** @var ReportSender|MockInterface $reportSender */
		$reportSender = Mockery::mock(ReportSender::class)
			->shouldReceive('send')
			->withArgs([$feed])
			->once()
			->getMock();

		$job = new ReportSenderJob($feed, $reportSender);
		$job->run();
	}

}
