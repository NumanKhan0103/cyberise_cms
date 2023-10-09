<?php

namespace App\Models;

use App\Models\Blog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class BlogCategory extends Model
{
    use HasFactory;



    protected $fillable = [
        'title',
        'slug',
        'icon',
    ];


    // protected $cast = [
    //     'slug' => Str::slug()
    // ]


    // many to many relationship 
    public function blogs(): BelongsToMany // Updated return type to BelongsTo
    {
        return $this->belongsToMany(Blog::class, 'blog_blog_categories'); 
    }
}
