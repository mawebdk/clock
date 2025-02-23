<?php
namespace MawebDK\Clock\Test;

use DateTimeImmutable;
use DateTimeZone;
use MawebDK\Clock\Clock;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use Psr\Clock\ClockInterface;
use ReflectionClass;

class ClockTest extends TestCase
{
    public function testNow_DateAndTime()
    {
        $dateTimeBefore  = new DateTimeImmutable(datetime: 'now');
        $currentDateTime = Clock::getSingleton()->now();
        $dateTimeAfter   = new DateTimeImmutable(datetime: 'now');

        $this->assertGreaterThanOrEqual(
            minimum: $dateTimeBefore->format(format: 'Y-m-d H:i:s.u'),
            actual: $currentDateTime->format(format: 'Y-m-d H:i:s.u')
        );

        $this->assertLessThanOrEqual(
            maximum: $dateTimeAfter->format(format: 'Y-m-d H:i:s.u'),
            actual: $currentDateTime->format(format: 'Y-m-d H:i:s.u')
        );
    }

    public function testNow_TimeZone()
    {
        $this->assertSame(
            expected: 'UTC',
            actual: Clock::getSingleton()->now()->getTimezone()->getName()
        );
    }

    /**
     * @throws Exception
     */
    public function testMocking()
    {
        $mockedDateTimeImmutable = DateTimeImmutable::createFromFormat(
            format: 'Y-m-d H:i:s.u',
            datetime: '1999-12-31 23:59:59.999999',
            timezone: new DateTimeZone(timezone: 'UTC')
        );

        $mockedClock = $this->createMock(type: ClockInterface::class);
        $mockedClock
            ->method(constraint: 'now')
            ->willReturn($mockedDateTimeImmutable);

        $reflectionClass = new ReflectionClass(objectOrClass: Clock::class);
        $reflectionClass->setStaticPropertyValue(name: 'singleton', value: $mockedClock);

        $this->assertSame(
            expected: $mockedDateTimeImmutable->format(format: 'Y-m-d H:i:s.u'),
            actual: Clock::getSingleton()->now()->format(format: 'Y-m-d H:i:s.u')
        );
    }

    protected function tearDown(): void
    {
        // Reset singleton to ensure usage of correct date and time in subsequent tests.
        $reflectionClass = new ReflectionClass(objectOrClass: Clock::class);
        $reflectionClass->setStaticPropertyValue(name: 'singleton', value: null);
    }
}