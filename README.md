[![PHP CI](https://github.com/ForeachQ/page-analyzer/actions/workflows/php-ci.yml/badge.svg)](https://github.com/ForeachQ/page-analyzer/actions/workflows/php-ci.yml)
<a href="https://codeclimate.com/github/Foreachq/php-project-lvl3/maintainability"><img src="https://api.codeclimate.com/v1/badges/d1d05c4334d2654423b1/maintainability" /></a>
<a href="https://codeclimate.com/github/Foreachq/php-project-lvl3/test_coverage"><img src="https://api.codeclimate.com/v1/badges/d1d05c4334d2654423b1/test_coverage" /></a>

# Page Analyzer

«Page Analyzer» — a site that analyzes if the specified pages correspond SEO suitability criteria.

## Demo

Project demo can be viewed [<ins>**here**</ins>](http://foreachq-page-analyzer.herokuapp.com/).

## Description

Program is build in the form of a site where you can add url of the page and run SEO checks.

Project features:
- Page parsing for response code, page title, first `<h1>` tag and `<meta name="description" content="...">` content;
- Postgresql DB storage for added urls and check results;
- Smart testing (faking Http responses, usage of an in-memory sqlite DB);
- Sail containerization for easy to run local instances.

## Requirements

- Docker

## Installation

- Download package

``` bash
git clone https://github.com/Foreachq/page-analyzer
```

__OR__

``` bash
composer create-project foreachq/page-analyzer
```

- Setup project

``` bash
make setup
```

- Run local instance

``` bash
make up   # starting on localhost:80
```

- Stop local instance

``` bash
make down
```

