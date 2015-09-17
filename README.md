# Remote Taskboard Camera

Control a digital camera with gphoto2 in order to realize a web-based view to a physical taskboard (agile board) for remote teams.

The current version features some custom post processing with [ImageMagick](http://imagemagick.org/) and uses [CLImate](https://github.com/thephpleague/climate).

## Table of Contents

+ [Installation](#installation)
+ [Requirements](#requirements)
+ [Documentation](#documentation)
+ [Credits](#credits)

## Installation

Using [composer](https://packagist.org/packages/league/climate):

```bash
$ composer install
```

## Requirements

Tested with 

+ PHP 5.4
+ PHP 5.5
+ ImageMagick 6.7.7-10
+ ImageMagick 6.9.1-10

## Documentation

Please change $BASEDIR variable in file captureimage.sh before first usage.

Usage:
```bash
$ ./captureimage.sh
```

You may want to use the post-process.php script manually. Show full list of arguments:
```bash
$ php post-process.php --help
```