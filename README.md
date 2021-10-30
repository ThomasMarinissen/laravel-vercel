# Laravel Vercel

## Introduction
Laravel Vercel is a helper to bring your application online within minutes through [Vercel](https://vercel.com).   

## Requirements

Laravel Vercel requires your application to be compatible with PHP 7.4 or PHP 8.0 and Laravel 7.0+. Furthermore, a Vercel account is needed 

## Installation

You can install the package via composer:

```bash
$ composer require thomasmarinissen/laravel-vercel
```

Make the application reaady to be deployed:

```bash
$ php artisan vercel:install
```

The above command will add 3 files to your laravel installation.

#### /api/index.php

This is the Vercel application entry point, under normal circumstances, this file should be left alone.

#### .vercelignore

The Vercel ignore file, you can add any file here that should not end up on Vercel. Note that the `/vendor`
directory is in there. Vercel will automatically install your composer dependencies.

#### vercel.json

This is your Vercel configuration and tells Vercel what PHP runtime to use, how to forward any traffic to your
application and finally what environment values to use.

Any value that normally would have been defined in the `.env` file should be defined in the `env` part of your
`vercel.json` file. For convenience, there is also an APP_KEY value defined (the key is generated when running the
`php artisan vercel:install` command).
Any env value that you would not like to commit to git (like the APP_KEY or your database password), you can add through
the Vercel website or the Vercel command line tool.


### Install Vercel

```bash
$ npm i -g vercel
```

This will install the Vercel command line tool.

### Login to Vercel

```bash
$ vercel login
```

This will let you login into the Vercel command line tool (You do need an [Vercel account](https://vercel.com/signup)
for this).

### Deploy your application

```bash
$ vercel
```

This will deploy your application to Vercel, while doing so, it will ask some question before starting the deployment
(picking the default value will work for most applications).
