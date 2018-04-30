<?php

namespace LaPress\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use LaPress\Models\Scopes\PostTypeScope;
use LaPress\Models\Traits\HasMeta;
use LaPress\Models\UrlGenerators\PostUrlGenerator;
use LaPress\Support\Filters\Filterable;
use LaPress\Support\WordPress\WordPressPostContentFormatter;

/**
 * @author    Sebastian Szczepański
 * @copyright ably
 */
abstract class AbstractPost extends Model
{
    use HasMeta, Filterable;

    const CREATED_AT = 'post_date';
    const UPDATED_AT = 'post_modified';

    const STATUS_POST_PUBLISHED = 'publish';
    const STATUS_POST_DRAFT = 'draft';

    /**
     * @var string
     */
    protected $table = 'posts';

    /**
     * @var string
     */
    protected $postType = 'post';

    /**
     * @var bool
     */
    protected $isPostTypePublic = true;

    /**
     * @var array
     */
    protected $supportedFields = [];

    /**
     * @var array
     */
    protected $supportedTaxonomies = [];

    /**
     * @var string
     */
    protected $primaryKey = 'ID';

    /**
     * @var array
     */
    protected $dates = ['post_date', 'post_modified'];

    /**
     * @var array
     */
    protected $hidden = [
        'post_date_gmt',
        'post_modified_gmt',
        'ping_status',
        'comment_status',
        'to_ping',
    ];

    protected $with = ['meta'];

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        self::creating(function ($post) {
            $post->post_type = $post->getPostType();
        });

        self::saving(function ($item) {
            $key = ucfirst($item->post_type);

            app('cache')->flush();
        });

        static::addGlobalScope(new PostTypeScope());
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function thumbnail()
    {
        return $this->hasOne(ThumbnailMeta::class, 'post_id')
                    ->where('meta_key', '_thumbnail_id');
    }

    /**
     * taxonomy
     * Define a relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function taxonomies()
    {
        return $this->belongsToMany(Taxonomy::class, 'term_relationships', 'object_id', 'term_taxonomy_id');
    }

    /**
     * attachments
     * Define a relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function attachments()
    {
        return $this->hasMany(Post::class, 'post_parent')
                    ->where('post_type', 'attachment');
    }


    /**
     * @param Builder $query
     */
    public function scopePublished(Builder $query)
    {
        $query->where('post_status', static::STATUS_POST_PUBLISHED);
    }

    /**
     * @param Builder $query
     * @param string  $name
     * @return Model|null|object|static
     */
    public function scopeFindOneBy(Builder $query, string $name)
    {
        // fixme
        return $query->where('post_name', $name)->first();
    }

    /**
     * @param Builder $query
     * @param string  $name
     * @return Model|null|object|static
     */
    public function scopeFindOneByName(Builder $query, string $name)
    {
        return $query->where('post_name', $name)->first();
    }

    /**
     * @param Builder $query
     */
    public function scopeDrafts(Builder $query)
    {
        $query->where('post_status', static::STATUS_POST_DRAFT);
    }

    /**
     * @return string
     */
    public function getPostType(): string
    {
        return $this->postType;
    }

    /**
     * @return string
     */
    public function getSlug(): string
    {
        return $this->postType;
    }

    /**
     * @return bool
     */
    public function isPostTypePublic(): bool
    {
        return $this->isPostTypePublic;
    }

    /**
     * @return array
     */
    public function getSupportedFields(): array
    {
        return $this->supportedFields;
    }

    /**
     * @return array
     */
    public function getSupportedTaxonomies(): array
    {
        return $this->supportedTaxonomies;
    }

    public function getAnchorAttribute(): ?string
    {
        return $this->post_title;
    }

    public function getUrlAttribute(): ?string
    {
        return (new PostUrlGenerator($this))->get();
    }

    public function getClassesAttribute(): string
    {
        $classes = $this->meta->_menu_item_classes;

        if (empty($classes)) {
            return '';
        }

        return collect($classes)->implode(' ');
    }


    public function getBodyAttribute()
    {
        return WordPressPostContentFormatter::format($this->post_content);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'term_relationships', 'object_id', 'term_taxonomy_id')
                    ->where('taxonomy', 'category');
    }
    
    
}