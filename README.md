# Employee Allowance API

The task is to implement API for simple employee allowance for any employee of a company.


## Technical Requirements

### Original in Polish language

> * Pochwal się nam swoimi umiejętnościami.
> * Użyj dowolnego frameworka PHP.
> * Ważna jest dla nas architektura wybranego rozwiązania.
> * ~~Nie ma limitu czasowego na wykonanie zadania.~~

### Translation

* Show us your skills.
* Use any PHP framework.
* Architecture of chosen solution is important for us.
* ~~There is no time limit.~~

**NOTE**: I was asked to complete this task within 7 calendar days, but I did not have to agree to this (I did, obviously).


## Business Logic Requirements

### Original in Polish language

> * Delegacje mogą się odbywać tylko do poniższych krajów, gdzie obowiązują następujące stawki diet za dany dzień:
>     * PL: 10 PLN,
>     * DE: 50 PLN,
>     * GB: 75 PLN.
> * Data rozpoczęcia delegacji nie może być późniejsza niż data zakończenia delegacji.
> * Jednocześnie, pracownik może przebywać tylko na jednej delegacji.
> * Dieta za dzień należy się tylko wtedy, gdy pracownik w danym dniu przebywa minimum 8 godzin w delegacji.
> * Za sobotę i niedzielę nie należy się dieta.
> * Jeżeli delegacja trwa więcej niż 7 dni kalendarzowych, to wtedy stawka diety za każdy dzień następujący po 7 dniu kalendarzowym jest podwójna.

### Translation

* Delegation can take place for countries mentioned below only, with given allowance per day:
    * PL: 10 PLN,
    * DE: 50 PLN,
    * GB: 75 PLN.
* Start date of the delegation cannot be later than end date.
* Simultaneously, employee can attend single delegation only.
* Allowance is granted only, if employee was delegated full 8 hours of a specific day.
* Allowance is not granted for Saturday and Sunday.
* If allowance takes more than 7 calendar days, then allowance wage doubles for each day, after first 7 calendar days.

### Endpoints

#### Original in Polish language

> * *(POST)* Dodanie pracownika do systemu. Brak danych wejściowych, w odpowiedzi zwracany jest identyfikator użytkownika.
> * *(POST)* Dodanie delegacji dla użytkownika, z danymi wejściowymi:
>     * Data i godzina rozpoczęcia delegacji,
>     * Data i godzina zakończenia delegacji,
>     * Identyfikator pracownika,
>     * Kod kraju, dla którego odbywa się delegacja w formacie ISO 3166-1 alpha-2.
> * *(GET)* Lista delegacji dla użytkownika podanego na wejściu, wraz z sumaryczną kwotą diety przysługującą za każdą delegację w formacie:

```json
[
  {
    "start": "2020-04-20 08:00:00",
    "end": "2020-04-21 16:00:00",
    "country": "PL",
    "amount_due": 20,
    "currency": "PLN"
  },
  {
    "start": "2020-04-24 28:00:00",
    "end": "2020-04-28 16:00:00",
    "country": "DE",
    "amount_due": 150,
    "currency": "PLN"
  }
]
```

#### Translation

* *(POST)* Add employee to the system. No input data, return employee ID in a response.
* *(POST)* Add employee allowance, with given input data:
    * Allowance start date and time,
    * Allowance end date and time,
    * Employee ID,
    * Country code for given allowance in ISO 3166-1 alpha-2 format.
* *(GET)* List of delegations for given employee ID as input data, with summary amount of allowance granted for each delegation in given format (see JSON above).


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

* Mindento Sp. z o.o. - task description in Polish language
* Damian Szczerbiński - translation, local installation & run
