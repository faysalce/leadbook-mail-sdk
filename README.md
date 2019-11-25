
# LeadbookMail PHP Library


Before using this library, you must have a valid API Key. To get an API Key, please log in to your LeadbookMail account and generate one in the Settings page.

## Installation

The recommended way to install the LeadbookMail PHP Library is through composer.

```
# Install Composer
curl -sS https://getcomposer.org/installer | php
```

LeadbookMail requires php-http client (see [Setting up a Request Adapter](#setting-up-a-request-adapter)). There are several [providers](https://packagist.org/providers/php-http/client-implementation) available. If you were using guzzle6 your install might look like this.

```
composer require guzzlehttp/guzzle
composer require php-http/guzzle6-adapter
```

Next, run the Composer command to install the LeadbookMail PHP Library:

```
composer require faysalce/leadbook-mail
```

After installing, you need to require Composer's autoloader:

```php
require 'vendor/autoload.php';
use LeadbookMail\LeadbookMail;
```

## Setting up a Request Adapter

Because of dependency collision, we have opted to use a request adapter rather than
requiring a request library.  This means that your application will need to pass in
a request adapter to the constructor of the LeadbookMail Library.  We use the [HTTPlug](https://github.com/php-http/httplug) in LeadbookMail. Please visit their repo for a list of supported [clients and adapters](http://docs.php-http.org/en/latest/clients.html).  If you don't currently use a request library, you will
need to require one and create a client from it and pass it along. The example below uses the GuzzleHttp Client Library.

An Client can be setup like so:

```php
<?php
require 'vendor/autoload.php';

use LeadbookMail\LeadbookMail;
use GuzzleHttp\Client;
use Http\Adapter\Guzzle6\Client as GuzzleAdapter;

$httpClient = new GuzzleAdapter(new Client());
$sparky = new LeadbookMail($httpClient, ['key'=>'YOUR_API_KEY']);
?>
```
