# What is this?
Saracakis S.A. is the main Greek Distributor of Honda & Mitsubishi parts and vehicles. He monthly sends pricelists for both brands to his dealers in an ancient DOS flat-file format (CP737 encoding).

This is a PHP library to parse these files in order to update a CRM or eCommerce platform. Currently only parts supported. If someone has a vehicle pricelist of the same format feel free to share.

## Installation 🔧

```sh
composer require rallisf1/php-saracakis-pricelists
```

## How to use 🚀

Have the file uploaded where php can reach it and feed it to the class. You can also filter the results by product type (optional):

| Filter Value | Description |
| 0 (default) | Nothing is filtered out |
| 1 | Your results will only contain car parts |
| 2 | Your results will only contain motorcycle parts |
| 3 | Your results will only contain power tool parts |

_Common parts "HOT" will be included all the time_

It will thrown an exception if something goes wrong so catch it.

```php
try {
    $data = new rallisf1\PhpSaracakisPricelists\Parser($full_file_path, $filtering);
} catch (Exception $e) {
    echo 'Error: '. $e->getMessage();
}
```

`$data` is an object which shall contain an import number, the date of the provided data file and the products.

Check out the example by running `composer install` in the examples folder and open `honda.php` in your browser _(http server with PHP >=5.4 required)_. A sample of 34 products is included, replace that with your data file for a quick test.

## Contribution 🖇️

Feel free to fork. If you find a bug or got something great to add make a pull request!

## Authors ✒️

* ** John Rallis ** - * Initial Work * - [rallisf1](https://github.com/rallisf1)

You can also look at the list of all the [contributors](https://github.com/rallisf1/php-saracakis-pricelists/contributors) who have participated in this project. 

## License 📄

This project is free to use, edit & distribute under the MIT License.

## Expressions of Gratitude 🎁

* Tell others about this project 📢 
* Buy me a beer 🍺 or coffee ☕ | ₿ [Crypto](https://freewallet.org/id/rallisf1/) |💰 [Cash](https://www.paypal.me/rallisf1) 
* Publicly thanks 🤓

---
⌨️ with ❤️ by  [rallisf1](https://github.com/rallisf1) 😊
