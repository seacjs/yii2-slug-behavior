# Yii2 slug behavior

[Yii2](http://www.yiiframework.com) slug behavior

## Installation

### Composer

The preferred way to install this extension is through [Composer](http://getcomposer.org/).

Either run ```php composer.phar require seacjs/yii2-slug-behavior "*"```

or add ```"seacjs/yii2-slug-behavior": "*"``` to the require section of your ```composer.json```

### Using

Attach the behavior in your model:

```php
public function behaviors()
{
    return [
        'slug' => [
            'class' => 'seacjs\behaviors\Slug',
            'slugAttribute' => 'slug',
            'attribute' => 'name',
            'translit' => true,
        ]
    ];
}
```
```
