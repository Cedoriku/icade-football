<?php
namespace App\Service;

use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;


class ApiHandler
{
    public const COUNTRY_CODE = 'FR';
    public const LEAGUE_ONE_ID = 61;
    public const SEASON = '2021';
    public const LEAGUES_URL = 'leagues';
    public const FIXTURES_URL = 'fixtures';
    public const STATS_URL = 'fixtures/statistics';
    public const LAST_MATCHES_NUMBER = 10;
    private HttpClientInterface $client;
    private string $apiHost;
    private string $headerHost;
    private string $apiKey;

    public function __construct($apiHost, $headerHost, $apiKey, HttpClientInterface $client)
    {
        $this->client = $client;
        $this->apiHost = $apiHost;
        $this->headerHost = $headerHost;
        $this->apiKey = $apiKey;
    }

    /**
     * @return array
     */
    public function getMatchesForLeagueOne(): array
    {
        return $this->request(
            self::FIXTURES_URL,
            [
                'league' => self::LEAGUE_ONE_ID,
                'season' => self::SEASON,
            ]
        );
    }

    public function getMatchesForAllFrenchLeagues(): array
    {
        $leagues = $this->request(
            self::LEAGUES_URL,
            [
                'code' => self::COUNTRY_CODE,
            ]
        );

        foreach ($leagues as $league) {
            $fixtures = $this->request(
                self::FIXTURES_URL,
                [
                    'league' => $league->league->id,
                    'season' => self::SEASON,
                ]
            );

            foreach ($fixtures as $fixture) {
                $stats = $this->request(
                    self::STATS_URL,
                    [
                        'fixture' => $fixture->fixture->id,
                    ]
                );
                $fixture->stats = $stats;
            }// foreach fixtures

            $league->fixtures = $fixtures;
        }// foreach leagues

        return $leagues;
    }

    public function getMatchDetail(int $id): array
    {
        $data = [];
        $data['match'] = $this->request(
            self::FIXTURES_URL,
            [
                'id' => $id,
            ]
        )[0];
        foreach ($data['match']->statistics as $stat) {
            $data['teamsFixtures'][$stat->team->name] = $this->request(
                self::FIXTURES_URL,
                [
                    'team' => $stat->team->id,
                    'last' => self::LAST_MATCHES_NUMBER
                ]
            );
        }

        return $data;
    }

    private function request(string $url, array $query)
    {
        $response = $this->client->request(
            'GET',
            $this->apiHost . $url,
            [
                'headers' => [
                    'x-rapidapi-host' => $this->headerHost,
                    'x-rapidapi-key' => $this->apiKey
                ],
                'query' => $query
            ]
        );

        return json_decode($response->getContent())->response;
    }

}