# Currency Exchange

The task is to implement simple currency exchange feature.


## Technical Requirements

### Original in Polish language
> Proszę napisać próbkę kodu zgodną z poniższymi wymaganiami biznesowymi oraz:
> * Rozwiązanie zamodelowane w konwencji Domain Driven Design,
> * PHP w wersji 8.*, 
> * Framework-agnostic, 
> * Całość przetestowana testami jednostkowymi.

### Translation

Please write a code sample according to business requirements mentioned in the next chapter, and:
* Solution written according to Domain Driven Design architecture,
* PHP in version 8.*,
* Framework-agnostic,
* Everything is tested with unit tests.


## Business Logic Requirements

### Original in Polish language

> Zadanie "Wymiana walut":
>
> * Założenia:
>     * Istnieją następujące kursy wymiany walut:
>         * EUR -> GBP 1.5678 
>         * GBP -> EUR 1.5432 
>     * Klientowi naliczana jest opłata w wysokości 1% od kwoty:
>         * Wypłacanej klientowi w przypadku sprzedaży 
>         * Pobieranej od klienta w przypadku zakupu 
> * Przypadki:
>     * Klient sprzedaje 100 EUR za GBP 
>     * Klient kupuje 100 GBP za EUR 
>     * Klient sprzedaje 100 GBP za EUR 
>     * Klient kupuje 100 EUR za GBP

### Translation

Task "Currency exchange":
* Assumptions:
    * Possible ways of currency exchange:
        * EUR -> GBP 1.5678
        * GBP -> EUR 1.5432
    * Customer is charged 1% fee of transaction value:
        * Transferred to customer in case of sale
        * Transferred from customer in case of purchase
* Cases:
    * Customer sell 100 EUR for GBP
    * Customer buy 100 GBP for EUR
    * Customer sell 100 GPB for EUR
    * Customer buy 100 EUR for GBP


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

* IFX Payments Europe Sp. z o.o. - task description in Polish language
* Damian Szczerbiński - translation, local installation & run
