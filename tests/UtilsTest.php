<?php

namespace pjpawel\Magis\Test;

use PHPUnit\Framework\TestCase;

class UtilsTest extends TestCase
{

    public const LOG_DIR = __DIR__ . '/../var/';
    public const LOG_FILE = self::LOG_DIR . 'test.log';

    public static function ensureLogFileExists(): void
    {
        if (!is_file(self::LOG_FILE)) {
            if (!is_dir(self::LOG_DIR)) {
                if (!mkdir(self::LOG_DIR)) {
                    throw new \Exception('Cannot create var/ directory');
                }
            }
            if (!file_put_contents(self::LOG_FILE, '')) {

            }
        }
    }

    public function testFileExist(): void
    {
        self::ensureLogFileExists();
        $this->assertFileExists(self::LOG_FILE);
    }

    public static function log(string $class, string $message): void
    {
        file_put_contents(self::LOG_FILE,
            sprintf('%s %s%s' . PHP_EOL,
                (new \DateTime('now'))->format(DATE_ATOM),
                $class,
                $message),
            FILE_APPEND
        );
    }

}