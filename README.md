# Wordpress Custom Theme library
This library is intended to give WordPress developers a little extra functionality when building custom themes.

This library includes advanced theme templates with variable injection, API wrapper for easier endpoint development, and a simple  caching system that stores cached objects to the file system.

## Install

You can install with composer
```shell
composer require thezombieguy/wpc 2.0
```

## Usage
More information is available in the docs folder.

### Theme

First create a template in your theme folder. For this example, we will create templates/test.php
```php
<?php print $test; ?>
```
And now, in one of your page templates, add the following code.

```php
<?php print wpc_theme('templates/test', array('test' => 'hello world')); ?>
```
The wpc_theme function takes 2 parameters. The location of the template relative to your wordpress theme directory (not that you do not need to provide the php extension, just the name), and a set of variables that you want to pass to your template.

Each array variable will be passed to the template and extracted as its own variable. You may also access the Theme class directly by invoking the \WPC\Theme class.

```php
$template = 'templates\test'
$theme = new \WPC\Theme($template);
$theme->set('test', 'hello world');
$content = $theme->fetch();
print $content;
```

## Cache

Create a new cache object. This will create a wpc_cache folder in your uploads folder if it doesn't already exist. Make sure you have the correct permissions.
```php
$cache = new Cache(); 
```

Now cache some data to the filesystem.

```php
$cache->set('myCacheData', array('fruit' => 'apple')); 
```
Once it is cached, you can retrieve it later.

```php
$myCacheData = $cache->get('myCacheData');
```

Cache returns an object when calling cached data. 
    `$myCacheData->time` represents when this ws cached. 
`$myCacheData->data` is the data you put into the cache.
```
stdClass Object
(
    [data] => Array
        (
            [fruit] => apple
        )

    [time] => 1489257366
)
```

You can check the `$myCacheData->time` and after a certain amount of time, you can delete and recache trhe object again with updated information.

You can also wipe the cache folder with 
```php
$cache->clear();
```

This will destory all cached objects.

## API

Create endpoints that will call back to a custom PHP class. 

First create a callback class that will handle your endpoint.

```php
class MyClass
{ 
    public function myMethod($args)
    {
        wp_json_send($args);
    }
}
```

Now create an array of endpoints to wish to register. Note the handler/callback parameters in the url string must match you class/method created above.

```php
$endpoint = array(
    'regex' => '^api/numbers/([0-9]+)/([0-9]+)',
    'redirect' => 'index.php?__api=1&handler=MyClass&callback=myMethod&uid=$matches[1]&prize_id=$matches[2]',
    'after' => 'top',
)

$endpoints[] = $endpoint;

new WPC\Api($endpoints);
```
Now go and update your permalinks in WordPress or you will not see this in action. Go to Settings->Permalinks and click Save changes.

Now when you go to your endpoint url http://example.com/api/numbers/1/2 you will see a json string output with the values you specified.

The redirect string MUST contain the __api, handler, and callback variables or your endpoint will not execute.
