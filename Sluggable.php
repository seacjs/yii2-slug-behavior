<?php

namespace seacjs\behaviors;
use yii\helpers\Inflector;
use yii\behaviors\SluggableBehavior;
use dosamigos\transliterator\TransliteratorHelper;

/**
 * Sluggable behavior
 *
 */

class Sluggable extends SluggableBehavior
{

    public $slugAttribute = 'slug';

    protected function generateSlug($slugParts)
    {
        return Inflector::slug( TransliteratorHelper::process(implode('-', $slugParts)), '-', true );
    }

}
