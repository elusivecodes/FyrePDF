# FyrePDF

**FyrePDF** is a free, open-source PDF generation library for *PHP*.


## Table Of Contents
- [Installation](#installation)
- [Basic Usage](#basic-usage)
- [Methods](#methods)
- [Static Methods](#static-methods)



## Installation

**Dependencies**

- Google Chrome

In Ubuntu:

```
wget https://dl.google.com/linux/direct/google-chrome-stable_current_amd64.deb
sudo apt install ./google-chrome-stable_current_amd64.deb
```

**Using Composer**

```
composer require fyre/pdf
```

In PHP:

```php
use Fyre\Utility\Pdf;
```


## Basic Usage

- `$source` is a string representing the source URL or HTML file.

```php
$pdf = new Pdf($source);
```

**From HTML**

Generate a *Pdf* from a HTML string.

```php
$pdf = Pdf::fromHtml($html);
```

**From URL**

Generate a *Pdf* from a URL or file path.

- `$url` is a string representing the source URL or HTML file.

```php
$pdf = Pdf::fromUrl($url);
```


## Methods

**To Binary**

Get the binary data.

```php
$data = $pdf->toBinary();
```

**Save**

Save the pdf as a file.

- `$filePath` is a string representing the file path.

```php
$pdf->save($filePath);
```


## Static Methods

**Get Binary Path**

Get the Chrome binary path.

```php
$binaryPath = Pdf::getBinaryPath();
```

**Get Timeout**

Get the timeout.

```php
$timeout = Pdf::getTimeout();
```

**Set Binary Path**

Set the Chrome binary path.

- `$binaryPath` is a string representing the Chrome binary path.

```php
Pdf::setBinaryPath($binaryPath);
```

**Set Timeout**

Set the timeout.

- `$timeout` is a number representing the timeout.

```php
Pdf::setTimeout($timeout);
```