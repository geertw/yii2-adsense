Google AdSense widget
=====================
Widget for displaying Google AdSense banners

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist geertw/yii2-adsense "*"
```

or add

```
"geertw/yii2-adsense": "*"
```

to the require section of your `composer.json` file.


Usage
-----

Once the extension is installed, simply use it in your code by  :

```php
<?= \geertw\Yii2\Adsense\AdsenseWidget::widget(); ?>```
