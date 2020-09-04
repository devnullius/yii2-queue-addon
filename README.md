Queue Addon
===========
Addon for yii2-queue original extension

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist devnullius/yii2-queue-addon "^1.0"
```

or add

```
"devnullius/yii2-queue-addon": "^1.0"
```

to the "require" section of your `composer.json` file.

_**However, note that yii2-queue extension must be configured and running.**_

Configs
-------

Add SetUp bootstrap class to common main.php in bootstrap section after "queue".

```php
        use devnullius\queue\addon\bootstrap\SetUp as QueueAddonSetUp;

        'bootstrap' => [
            'queue',
            QueueAddonSetUp::class
        ]
```

For binding events with listeners use following style in your bootstrap.(Or something like that;-)

```php
        use devnullius\queue\addon\dispatchers\SimpleEventDispatcher;
        use yii\di\Container;
        use devnullius\queue\addon\example\TestExampleEvent;
        use devnullius\queue\addon\example\TestExampleListener;
        
        $container->setSingleton(SimpleEventDispatcher::class,
        static function (Container $container) {
            return new SimpleEventDispatcher($container, [
                TestExampleEvent::class => [TestExampleListener::class],
            ]);
        });
```

Usage
-----

Once the extension installed, simply you can take a look on examples inside, running them in your framework environment. Something like this.

```php
        use devnullius\queue\addon\example\TestEventService;

        $testService = Yii::createObject(TestEventService::class);
        $testService->testEvent('Event generation no ... ');
```

After that, you can run queue/listen, in --verbose mode, to see how listener/handler work.

```
        php yii queue/listen --verbose
```
