<?php

namespace seacjs\behaviors;

use yii;
use yii\base\Behavior;
use yii\db\ActiveRecord;
use yii\helpers\Inflector;
use dosamigos\transliterator\TransliteratorHelper;

class Slug extends Behavior
{

    public $slugAttribute = 'slug';
    public $attribute = 'name';
    public $translit = true;

    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_VALIDATE => 'getSlug'
        ];
    }

    public function getSlug($event)
    {
        if (empty($this->owner->{$this->slugAttribute})) {
            $this->owner->{$this->slugAttribute} = $this->generateSlug($this->owner->{$this->attribute});
        } else {
            $this->owner->{$this->slugAttribute} = $this->generateSlug($this->owner->{$this->slugAttribute});
        }
    }

    private function generateSlug($slug)
    {
        $slug = $this->slugify( $slug );
        if ( $this->checkUniqueSlug( $slug ) ) {
            return $slug;
        } else {
            for ( $suffix = 2; !$this->checkUniqueSlug( $new_slug = $slug . '-' . $suffix ); $suffix++ ) {}
            return $new_slug;
        }
    }

    private function slugify($slug)
    {
        if ($this->translit) {
            return Inflector::slug(TransliteratorHelper::process($slug), '-', true);
        } else {
            return $this->slug($slug, '-', true);
        }
    }

    private function slug($string, $replacement = '-', $lowercase = true)
    {
        $string = preg_replace('/[^\p{L}\p{Nd}]+/u', $replacement, $string);
        $string = trim($string, $replacement);
        return $lowercase ? strtolower($string) : $string;
    }

    private function checkUniqueSlug($slug)
    {
        $pk = $this->owner->primaryKey();
        $pk = $pk[0];

        $condition = $this->out_attribute . ' = :out_attribute';
        $params = [ ':out_attribute' => $slug ];
        if (!$this->owner->isNewRecord) {
            $condition .= ' and ' . $pk . ' != :pk';
            $params[':pk'] = $this->owner->{$pk};
        }

        return !$this->owner->find()
            ->where($condition, $params)
            ->one();
    }
}
