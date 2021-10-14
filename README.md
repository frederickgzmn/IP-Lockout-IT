# IP-Lockout-IT plugin

It is a simple releaser for ip addresses locked out for ithemes.

## Requirements

* `Composer installed`
* `PHP 7.2+`

## Plugin features
* Cache based on [Transient API](https://developer.wordpress.org/apis/handbook/transients/)
* Wordpress Hooks available for expand the plugin
* Namespace based

## WordPress Preparation
You only need a [Wordpress](https://wordpress.org/download/) fresh copy installed and configured and pull the repository on the `WordpressROOT/wp-content/plugins` directory

## Installation
* The IP-Lockout-IT plugin can be installed directly into your plugins folder `WordpressROOT/wp-content/plugin` as mentioned before
* Go to the plugin directory using `cd ip-lockout-it/`(Keeping in mind, the plugin folder name it is `ip-lockout-it`)
* You should be able to run `composer install
* Let's start using the plugin


##### Installing composer V1
* Download [Composer](https://getcomposer.org/download/) using the `Command-line installation` section
* And run the 3rd step using `php composer-setup.php --version=1.0.0-alpha8` instead of `php composer-setup.php`
* Now you will have a `composer.phar` file in the plugin folder
* You can run composer V1 by running `php composer.phar install`

## License
This WordPress Plugin is licensed under the GPL v2 or later.

## Author
Frederic Guzman Santana