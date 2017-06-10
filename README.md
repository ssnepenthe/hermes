# hermes
A configuration based web scraper.

This repo is the first (only?) pass at an idea that has been floating around in my head for a while.

It is fully functional, but it is neither complete nor particularly well written.

**I DO NOT RECOMMEND THAT YOU USE THIS FOR ANYTHING**

With that out of the way...

## Requirements
Composer and PHP 7.0 or greater.

## Installation
This package is not on Packagist. You will have to include this repo in your [composer.json repositories](https://getcomposer.org/doc/04-schema.md#repositories) to use it.

## Goals
This package aims to be a framework for creating simple and maintainable scrapers with limited PHP experience.

The general idea is to allow scrapers to be written as config files in any arbitrary format (e.g. PHP, JSON, YAML).

Scrapers should have a method for declaring what sites they support.

Scrapers should be able to extract data given nothing more than a CSS selector.

Scrapers should provide distinct steps for extraction, normalization and transformation of data.

Scrapers should be able to extend other scrapers, overriding individual properties as necessary.

## Usage
*As mentioned above, this package is far from complete and therefore subject to change at any time.*

Only the extraction portion of scraping is covered: You will need to handle the HTTP/crawler portion on your own. Alternatively, just use [Goutte](https://github.com/FriendsOfPHP/Goutte).

As a simple example, create a file named `duckduckgo.com.php` with the following contents:

```
<?php

return [
    'schema' => [
        [
            'name' => 'results',
            'selector' => '.web-result',
            'schema' => [
                [
                    'name' => 'title',
                    'selector' => '.result__title',
                ],
                [
                    'name' => 'description',
                    'selector' => '.result__snippet',
                ],
            ],
        ],
    ],
];
```

Create a scraper instance using the `ScraperFactory` class:

```
$scraper = SSNepenthe\Hermes\Scraper\ScraperFactory::fromConfigFile('/path/to/duckduckgo.com.php');
```

The scraper works against a Symfony DOM Crawler instance. Create this however you see fit - The example below uses Goutte:

```
$client = new Goutte\Client;
$crawler = $client->request('GET', 'https://duckduckgo.com/html?q=firefox');
```

And lastly, pass the crawler to the scrape method on the scraper instance:

```
$result = $scraper->scrape($crawler);
```

You will wind up with an array that looks like the following:

```
[
    'results' => [
        [
            'title' => 'Download Firefox — Free Web Browser — Mozilla',
            'description' => 'Download Mozilla Firefox, a free Web browser. Firefox is created by a global non-profit dedicated to putting individuals in control online. Get Firefox for Windows ...',
        ],
        [
            'title' => 'Firefox - Home | Facebook',
            'description' => 'Firefox. 18,714,317 likes · 14,556 talking about this. The only browser built for freedom, not for profit. Get Firefox: https://mzl.la/292SfT5.',
        ],
        [
            'title' => 'Firefox 🦊🌍 (@firefox) | Twitter',
            'description' => 'The latest Tweets from Firefox (@firefox). go forth and internet freely. All over the world',
        ],
        // ...
    ],
]
```

For more examples, check out the various files in `tests/fixtures/scrapers`.
