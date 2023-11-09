<?php

declare(strict_types=1);

namespace Kodkod\InterviewTask\MovieRecommendation\Test\Unit\Movie;

use Kodkod\InterviewTask\MovieRecommendation\Filter;
use Kodkod\InterviewTask\MovieRecommendation\Movie\Movie;
use Kodkod\InterviewTask\MovieRecommendation\Movie\MovieRepository;
use Kodkod\InterviewTask\MovieRecommendation\Movie\MovieService;
use Kodkod\InterviewTask\MovieRecommendation\Test\Unit\FilterTest;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\DependsOnClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

use function count;

final class MovieRepositoryTest extends TestCase
{
    private const FIXTURE_MOVIES_ENTRY_COUNT = 85;

    private MovieService|MockObject $movieServiceMock;

    protected function setUp(): void
    {
        $this->movieServiceMock = $this->createMock(MovieService::class);
        $this->movieServiceMock
            ->method('fromArray')
            ->willReturnCallback(
                /** @var array<string, string> $plainArr */
                fn (array $plainArr) => new Movie($plainArr['title'])
            );
    }

    #[Test]
    public function emptyMoviesPassedToConstructor(): void
    {
        $repository = $this->createRepository();
        $movies = [];
        foreach ($repository->getAll() as $movie) {
            $movies[] = $movie;

            self::assertInstanceOf(Movie::class, $movie);
        }

        // must match amount of entries in fixtures/movies.php file
        self::assertCount(self::FIXTURE_MOVIES_ENTRY_COUNT, $movies);
    }

    #[Test]
    public function nonEmptyMoviesPassedToConstructor(): void
    {
        $passedMovies = [
            $this->createMock(Movie::class),
            $this->createMock(Movie::class),
            $this->createMock(Movie::class),
        ];

        $repository = $this->createRepository($passedMovies);
        $movies = [];
        foreach ($repository->getAll() as $movie) {
            $movies[] = $movie;

            self::assertInstanceOf(Movie::class, $movie);
        }

        self::assertCount(3, $movies);
    }

    #[Test]
    public function countMovies(): void
    {
        $passedMovies = [
            $this->createMock(Movie::class),
            $this->createMock(Movie::class),
            $this->createMock(Movie::class),
            $this->createMock(Movie::class),
        ];

        $repository = $this->createRepository($passedMovies);

        self::assertSame(4, $repository->countAll());
    }

    /**
     * @param Movie[] $expectedResult
     */
    #[Test]
    #[DependsOnClass(FilterTest::class)]
    #[DataProvider('provideGetByFilter')]
    public function getByFilter(Filter $filter, array $expectedResult): void
    {
        $repository = $this->createRepository();

        $result = iterator_to_array($repository->getByFilter($filter));

        self::assertCount(count($expectedResult), $result);
        foreach ($result as $key => $item) {
            self::assertInstanceOf(Movie::class, $item);
            self::assertSame($expectedResult[$key]->getTitle(), $item->getTitle());
        }
    }

    /**
     * @return array<array>
     */
    public static function provideGetByFilter(): array
    {
        $fixtures = self::loadMovieFixture();

        return [
            // #set 0
            [
                new Filter(),
                $fixtures,
            ],
            // #set 1
            [
                new Filter(titleStartsWith: 'W', titleLengthIsEven: true),
                [
                    new Movie('Whiplash'),
                    new Movie('Wyspa tajemnic'),
                    new Movie('Władca Pierścieni: Drużyna Pierścienia'),
                ],
            ],
            // set #2
            [
                new Filter(titleHasMinWords: 2),
                [
                    new Movie('Pulp Fiction'),
                    new Movie('Skazani na Shawshank'),
                    new Movie('Dwunastu gniewnych ludzi'),
                    new Movie('Ojciec chrzestny'),
                    new Movie('Leon zawodowiec'),
                    new Movie('Władca Pierścieni: Powrót króla'),
                    new Movie('Fight Club'),
                    new Movie('Forrest Gump'),
                    new Movie('Chłopaki nie płaczą'),
                    new Movie('Człowiek z blizną'),
                    new Movie('Doktor Strange'),
                    new Movie('Szeregowiec Ryan'),
                    new Movie('Lot nad kukułczym gniazdem'),
                    new Movie('Wielki Gatsby'),
                    new Movie('Avengers: Wojna bez granic'),
                    new Movie('Życie jest piękne'),
                    new Movie('Pożegnanie z Afryką'),
                    new Movie('Milczenie owiec'),
                    new Movie('Dzień świra'),
                    new Movie('Blade Runner'),
                    new Movie('Król Lew'),
                    new Movie('La La Land'),
                    new Movie('Wyspa tajemnic'),
                    new Movie('American Beauty'),
                    new Movie('Szósty zmysł'),
                    new Movie('Gwiezdne wojny: Nowa nadzieja'),
                    new Movie('Mroczny Rycerz'),
                    new Movie('Władca Pierścieni: Drużyna Pierścienia'),
                    new Movie('Harry Potter i Kamień Filozoficzny'),
                    new Movie('Green Mile'),
                    new Movie('Mad Max: Na drodze gniewu'),
                    new Movie('Terminator 2: Dzień sądu'),
                    new Movie('Piraci z Karaibów: Klątwa Czarnej Perły'),
                    new Movie('Truman Show'),
                    new Movie('Skazany na bluesa'),
                    new Movie('Gran Torino'),
                    new Movie('Mroczna wieża'),
                    new Movie('Casino Royale'),
                    new Movie('Piękny umysł'),
                    new Movie('Władca Pierścieni: Dwie wieże'),
                    new Movie('Zielona mila'),
                    new Movie('Requiem dla snu'),
                    new Movie('Forest Gump'),
                    new Movie('Requiem dla snu'),
                    new Movie('Milczenie owiec'),
                    new Movie('Breaking Bad'),
                    new Movie('Nagi instynkt'),
                    new Movie('Igrzyska śmierci'),
                    new Movie('Siedem dusz'),
                    new Movie('Dzień świra'),
                    new Movie('Pan życia i śmierci'),
                    new Movie('Hobbit: Niezwykła podróż'),
                    new Movie('Pachnidło: Historia mordercy'),
                    new Movie('Wielki Gatsby'),
                    new Movie('Sin City'),
                    new Movie('Przeminęło z wiatrem'),
                    new Movie('Królowa śniegu'),
                ],
            ],
            // set #3
            [
                new Filter(titleStartsWith: 'Władca', titleHasMinWords: 3),
                [
                    new Movie('Władca Pierścieni: Powrót króla'),
                    new Movie('Władca Pierścieni: Drużyna Pierścienia'),
                    new Movie('Władca Pierścieni: Dwie wieże'),
                ],
            ],
            // set #4
            [
                new Filter(titleStartsWith: 'W', titleLengthIsEven: true, titleHasMinWords: 2),
                [
                    new Movie('Wyspa tajemnic'),
                    new Movie('Władca Pierścieni: Drużyna Pierścienia'),
                ],
            ],
        ];
    }

    /**
     * @param Movie[]|null $movies
     */
    private function createRepository(?array $movies = null): MovieRepository
    {
        return new MovieRepository($this->movieServiceMock, $movies);
    }

    /**
     * @return Movie[]
     */
    private static function loadMovieFixture(): array
    {
        /** @var string[] $movies */
        $movies = $movieFixtures = [];
        include dirname(__DIR__, 3).DIRECTORY_SEPARATOR.'fixtures'.DIRECTORY_SEPARATOR.'movies.php';

        foreach ($movies as $movieTitle) {
            $movieFixtures[] = new Movie($movieTitle);
        }

        return $movieFixtures;
    }
}
