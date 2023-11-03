# Evaluation Process

The task is to implement the business domain logic associated with the evaluation
process based on the description below.


## Technical Requirements

1. The assessment will focus primarily on the DDD approach to solving business problems.
2. Prepare a set of automated tests (PHPUnit) to verify the correctness of the implementation in accordance with business requirements.
3. **Tests must be runnable and green.**
4. **PHP language version min. 8.1.**
5. *If you plan to use a Framework or database you are limited to Symfony 5.4 and PostgreSQL 13.*


## Business Logic Requirements

1. The system allows the recording of assessments carried out with their evaluations.
2. The evaluation is carried out by the Supervisor.
3. The evaluation is carried out offline.
4. Assessment is carried out in the indicated Standard.
5. Clients can have multiple assessments in different standards.
6. The Client being evaluated must have an active contract with the Supervisor.
7. The Supervisor must have authority for the standard.
8. Upon completion of evaluation the assessment can have positive or negative ratings.
9. The assessment has an expiration date of 365 days counting from the day evaluation took place. After it is exceeded, the assessment expires.
10. It is possible to lock the assessment by suspension or withdrawn.
11. Suspended assessment can be unlocked.
12. Suspended assessment may be withdrawn.
13. Withdrawned assessment cannot be unlocked nor lock cannot be changed into suspension.
14. Expired assessment cannot be locked.
15. It is not possible to lock an assessment that is currently locked, it is necessary to unlock it in advance. Only changing Suspension into withdrawn is allowed.
16. Assessment lock should contain descriptive information about the operation performed.
17. Conducting further evaluation is carried out under the same standard. This means that it is possible to replace an assessment obtained in the standard X by obtaining an assessment in the same standard X.
18. If Client has an active assessment, the newly obtained assessment replaces the current one.
19. Subsequent evaluation may be conducted after a period of not less than 180 days for evaluation completed with a positive result and 30 days for evaluation completed with a negative result.

## Local Test Run

### Tools

[Docker with Docker Compose *v2*](https://docs.docker.com/get-docker/) **OR** locally installed:

* [PHP *v8.1 or higher*](https://www.php.net/downloads.php),
* [PHP composer v2](https://getcomposer.org/download/),
* [PHPUnit *v10.x*](https://phpunit.de/getting-started/phpunit-10.html).

### Install

Install composer packages using `docker compose run --rm composer install`.

### Test

Run tests using `docker compose run --rm phpunit`.

## Contributors of README

* Codete Global HQ - task description
* Damian Szczerbiński - local installation & run
