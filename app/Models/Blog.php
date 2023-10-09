<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Blog extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'thumbnail',
        'content',
        'user_id',
        'blog_category_id',
    ];


    protected $attribute = [
        'user_id' => 1,
    ];


 




    // protected $casts = [
    //     'blog_category_id' => 'array',
    // ];


    // relationships 


    public function blogCategory(): BelongsToMany // Updated return type to BelongsTo
    {
        return $this->belongsToMany(BlogCategory::class, 'blog_blog_categories'); 
    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }




}
