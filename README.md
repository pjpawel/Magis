# Magis

### *Services for symfony bundle for php templates rendering*

[![Latest Version on Packagist](https://img.shields.io/packagist/v/pjpawel/magis.svg?style=flat-square)](https://packagist.org/packages/pjpawel/magis)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/pjpawel/Magis/badges/quality-score.png?b=dev)](https://scrutinizer-ci.com/g/pjpawel/Magis/?branch=dev)
![PHPStanLevel](https://img.shields.io/badge/PHPStan-level%207-brightgreen.svg?style=flat)

## Why Magis?
### - It's incredible fast!
### - It's easy to understand!
### - It can implement all services you need!


## Install

If you want to install Magis as symfony bundle you should use [MagisBundle](https://github.com/pjpawel/MagisBundle)
```
composer require pjpawel/magis-bundle
```
If you want raw view service and view classes use (this repository)
```
composer require pjpawel/magis
```

## Library classes

```php
\pjpawel\Magis\ViewDispatcherService::class //service to dependency injection
\pjpawel\Magis\View\DirectView::class //view class that has method render() to ... render template :)
```

## Usage

You should use `ViewDispatcherService` as dependency injection service.
You can create custom service to render view or use create Views directly.
