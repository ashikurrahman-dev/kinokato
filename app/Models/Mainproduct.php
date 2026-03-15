<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Mainproduct extends Model
{
    use HasSlug;
    use HasFactory;

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('ProductName')
            ->saveSlugsTo('ProductSlug');
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyNameCategory()
    {
        return 'ProductSlug';
    }
}
