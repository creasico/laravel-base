<?php

declare(strict_types=1);

namespace Creasi\Tests;

use Facebook\WebDriver\Exception\TimeoutException;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use SebastianBergmann\CodeCoverage\Driver\Driver;

class BaseTest extends TestCase
{
    use WithFaker;

    #[Test]
    public function it_adds_dusk_macros()
    {
        $this->assertTrue(Browser::hasMacro('waitForInertia'));
    }

    #[Test]
    public function it_throws_an_exception_if_the_count_doesnt_increase()
    {
        $driver = $this->mock(Driver::class);

        $driver->shouldReceive('executeScript')
            ->with('return window.__inertiaNavigatedCount;')
            ->once()
            ->andReturn(0);

        $driver->shouldReceive('executeScript')
            ->with('return window.__inertiaNavigatedCount > 0;')
            ->andReturn(0);

        $browser = new Browser($driver);

        try {
            $browser->waitForInertia(0.1); // wait for 0.1 seconds
        } catch (TimeoutException $e) {
            return $this->assertTrue(true);
        }

        $this->fail('Should have thrown TimeoutException');
    }

    #[Test]
    public function it_passes_when_the_count_increases()
    {
        $driver = $this->mock(Driver::class);

        $driver->shouldReceive('executeScript')
            ->with('return window.__inertiaNavigatedCount;')
            ->once()
            ->andReturn(0);

        $driver->shouldReceive('executeScript')
            ->with('return window.__inertiaNavigatedCount > 0;')
            ->once()
            ->andReturn(1);

        $browser = new Browser($driver);
        $browser->waitForInertia(1);

        $this->assertTrue(true);
    }
}
