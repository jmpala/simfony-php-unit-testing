<?php

declare(strict_types=1);

namespace App\Tests\Unit\Entity\Service;

use App\Enum\HealthStatus;
use App\Service\GithubService;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class GithubServiceTest extends TestCase
{
    private LoggerInterface $mockLogger;
    private MockHttpClient $mockHttpClient;
    private MockResponse $mockResponse;

    protected function setUp(): void
    {
        $this->mockLogger = $this->createMock(LoggerInterface::class);
        $this->mockHttpClient = new MockHttpClient();
    }

    /**
     * @dataProvider dinoNameProvider
     */
    public function testGetHealthReportReturnsCorrectHealthStatusForDino(string $dinoName, HealthStatus $expectedStatus): void
    {
        $service = $this->createGithubService([
            [
                'title' => 'Daisy',
                'labels' => [
                    ['name' => 'Status: Sick'],
                ],
            ],
            [
                'title' => 'Maverick',
                'labels' => [
                    ['name' => 'Status: Healthy'],
                ],
            ]
        ]);

        self::assertSame($expectedStatus, $service->getHealthReport($dinoName));
        self::assertSame(1, $this->mockHttpClient->getRequestsCount());
        self::assertSame('GET', $this->mockResponse->getRequestMethod());
        self::assertSame('https://api.github.com/repos/SymfonyCasts/dino-park/issues', $this->mockResponse->getRequestUrl());
    }

    public function dinoNameProvider(): \Generator
    {
        yield 'Healthy Dino' => ['Maverick', HealthStatus::HEALTHY];
        yield 'Sick Dino' => ['Daisy', HealthStatus::SICK];
    }

    public function testExceptionThrownWithUnknownLabel(): void
    {
        $service = $this->createGithubService([
            [
                'title' => 'Maverick',
                'labels' => [
                    ['name' => 'Status: Silly'],
                ],
            ]
        ]);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Silly is an unknown status label!!!');
        $service->getHealthReport('Maverick');
    }

    public function createGithubService(array $responseData): GithubService
    {
        $this->mockResponse = new MockResponse(
            json_encode(
                $responseData,
                JSON_THROW_ON_ERROR
            )
        );

        $this->mockHttpClient->setResponseFactory($this->mockResponse);

        return new GithubService($this->mockHttpClient, $this->mockLogger);
    }
}