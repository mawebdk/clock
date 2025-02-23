<?php
namespace MawebDK\Clock;

use DateTimeImmutable;
use DateTimeZone;
use Throwable;
use Psr\Clock\ClockInterface;

/**
 * Representation of a clock for getting the current date and time.
 */
class Clock implements ClockInterface
{
    /**
     * @var ClockInterface|null   Singleton instance to handle the Clock requests.
     */
    private static ?ClockInterface $singleton = null;

    /**
     * Returns the singleton instance to handle the Clock requests.
     * @return ClockInterface   Singleton instance to handle the Clock requests.
     */
    public static function getSingleton(): ClockInterface
    {
        if (is_null(self::$singleton)):
            self::$singleton = new self();
        endif;

        return self::$singleton;
    }

    /**
     * Returns the current UTC time as a DateTimeImmutable object.
     * @throws ClockException   Failed to create an instance of DateTimeImmutable with the current UTC date and time.
     */
    public function now(): DateTimeImmutable
    {
        try {
            return new DateTimeImmutable(datetime: 'now', timezone: new DateTimeZone(timezone: 'UTC'));
        } catch (Throwable $e) {
            throw new ClockException(
                message: 'Failed to create an instance of DateTimeImmutable with the current UTC date and time.',
                previous: $e
            );
        }
    }

    /**
     * Private constructor to avoid direct instantiation.
     */
    private function __construct()
    {
        // This body is empty on purpose.
    }
}