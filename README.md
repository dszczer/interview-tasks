# Movie Recommendation Feature

The task is to implement business domain logic associated with movie recommendation search feature.

## Technical Requirements

### Original in Polish language

> Napisz prostą aplikację w PHP do wyszukiwania rekomendacji filmów (wystarczy sama część backendowa).
> Lista filmów w formie tablicy jest dostarczona w pliku movies.php, możesz ją skopiować lub bezpośrednio dodać do Twojej aplikacji.
> Napisz testy jednostkowe, które sprawdzą poprawność rozwiązania.

### Translation

Write a simple PHP application for movie recommendation search feature (backend only is sufficient).
Title's list in form of a PHP array is attached in `fixtures` directory. You can copy it or use directly in your implementation.
Write unit tests which will verify correctness of implementation.


## Business Logic Requirements

### Original in Polish language

> Aplikacja zawiera 3 algorytmy rekomendacji:
> 1) Zwracane są 3 losowe tytuły.
> 2) Zwracane są wszystkie filmy na literę ‘W’ ale tylko jeśli mają parzystą liczbę znaków w tytule.
> 3) Zwracany są wszystkie tytuły, które składają się z więcej niż 1 słowa.

### Translation

Feature is composed of 3 recommendation algorithms:

1. Return 3 random movie titles.
2. Return all titles starting with *W* letter, but if amount of title's character is even **only**.
3. Return all titles which contains more than one word.


## Local Test Run

### Requirements

[Docker with Docker Compose *v2*](https://docs.docker.com/get-docker/) **OR** locally installed:

* [PHP *v8.1 or higher*](https://www.php.net/downloads.php),
* [PHP composer v2](https://getcomposer.org/download/),
* [PHPUnit *v10.x*](https://phpunit.de/getting-started/phpunit-10.html).

### Install

*All commands below assume, that You run them in the main project directory*.

* **Docker option:**
    1. Install docker compose, using instructions from the hyperlink above.
    2. Install composer packages using `docker compose run --rm composer install`.
* **Local tools option:**
    1. Install PHP, either using your Linux/Windows distribution or download code and compile it from the hyperlink above.
    2. Install PHP Composer and PHPUnit PHAR files according to instructions included in hyperlinks above, into the main project directory.
    3. Install composer packages using `php composer.phar install`.

### Test

Run tests using:
* If you've followed **Docker option** installation steps: `docker compose run --rm phpunit`.
* If you've followed **Local tools option** installation steps: `php phpunit.phar`.

**NOTE:** `phpunit.xml` file contains runtime configuration, so there is no need to pass it to the command.

**NOTE:** code coverage and static analysis report can be seen at neat web page `var/log/index.html`.


## Contributors of README

* Morele.net Sp. z o.o. - task description in Polish language
* Damian Szczerbiński - translation, local installation & run
