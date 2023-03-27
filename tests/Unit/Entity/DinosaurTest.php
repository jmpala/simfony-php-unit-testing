<?php

declare(strict_types=1);

namespace App\Tests\Unit\Entity;

use App\Entity\Dinosaur;
use App\Enum\HealthStatus;
use PHPUnit\Framework\TestCase;

class DinosaurTest extends TestCase
{
    public function testCanGetAndSetData(): void
    {
        $dino = new Dinosaur('Tyrannosaurus', 'Carnivorous', 12, 'Main');

        self::assertSame('Tyrannosaurus', $dino->getName());
        self::assertSame('Carnivorous', $dino->getGenus());
        self::assertSame(12, $dino->getLength());
        self::assertSame('Main', $dino->getEnclosure());
    }

    /**
     * @dataProvider sizeDescriptionProvider
     */
    public function testDinoHasCorrectSizeDescriptionFromLength(int $length, string $expectedSize): void
    {
        $dino = new Dinosaur('Tyrannosaurus', 'Carnivorous', $length, 'Main');

        self::assertSame($expectedSize, $dino->getSizeDescription());
    }

    public function sizeDescriptionProvider(): \Generator
    {
        yield '10 Meter Length Dino' => [10, 'Large'];
        yield '5 Meter Length Dino' => [5, 'Medium'];
        Yield '4 Meter Length Dino' => [4, 'Small'];
    }

    public function testISAcceptingVisitorsByDefault(): void
    {
        $dino = new Dinosaur('Tyrannosaurus', 'Carnivorous', 12, 'Main');

        self::assertTrue($dino->isAcceptingVisitors());
    }

    /**
     * @dataProvider healthStatusProvider
     */
    public function testIsNotAcceptingVisitorsIfSick(HealthStatus $status, bool $acceptingVisitors): void
    {
        $dino = new Dinosaur('Tyrannosaurus', 'Carnivorous', 12, 'Main');

        $dino->setHealth($status);

        self::assertSame($acceptingVisitors, $dino->isAcceptingVisitors());
    }

    public function healthStatusProvider(): \Generator
    {
        yield 'Sick dinos not accepting visitors' => [HealthStatus::SICK, false];
        yield 'Hungry dinos accepting visitors' => [HealthStatus::HUNGRY, true];
        yield 'Healthy dinos accepting visitors' => [HealthStatus::HEALTHY, true];
    }
}