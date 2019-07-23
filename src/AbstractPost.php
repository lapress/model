<?php

namespace LaPress\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use LaPress\Content\Str;
use LaPress\Models\Scopes\PostTypeScope;
use LaPress\Models\Traits\HasMeta;
use LaPress\Models\UrlGenerators\PostUrlGenerator;
use LaPress\Models\UrlGenerators\UrlGeneratorResolver;
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
     * @var array
     */
    protected $guarded = [];

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
    protected $attributes = [
        'post_content_filtered' => '',
        'to_ping'               => '',
        'pinged'                => '',
        'post_content'          => '',
        'post_excerpt'          => '',
    ];

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

    /**
     * @var array
     */
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
            if (empty($post->post_name)) {
                $post->post_name = str_slug($post->post_title);
            }
            $date = Carbon::now();

            if (empty($post->post_date_gmt)) {
                $post->post_date_gmt = $date->subHour();
            }

            if (empty($post->post_modified_gmt)) {
                $post->post_modified_gmt = $date->subHour();
            }

            $post->post_type = $post->getPostType();
        });

        self::saving(function ($item) {
            $key = ucfirst($item->post_type);

            app('cache')->flush();
        });

        static::addGlobalScope(new PostTypeScope());
    }

    /**
     * @return bool
     */
    public function hasThumbnail()
    {
        return $this->thumbnail && !empty($this->thumbnail->size);
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
     * @return string
     */
    public function getDate($format = null)
    {
        return $this->post_date->format($format ?: config('wordpress.date-format', 'd.m.Y'));
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
     * @param string $modelClass
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function getTaxonomyRelationship(string $modelClass)
    {
        return $this->belongsToMany($modelClass, 'term_relationships', 'object_id', 'term_taxonomy_id')
                    ->where('taxonomy', $modelClass::TAXONOMY_KEY);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function author()
    {
        return $this->belongsTo($this->getLocalizedModel('User'), 'post_author', 'ID');
    }

    /**
     * @param string $name
     * @return string
     */
    public function getLocalizedModel(string $name): string
    {
        return class_exists('App\\Models\\'.$name)
            ? 'App\\Models\\'.$name
            : 'LaPress\\Models\\'.$name;
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
     * @return bool
     */
    public function isPublic(): bool
    {
        return $this->post_status == static::STATUS_POST_PUBLISHED;
    }

    /**
     * @return bool
     */
    public function isDraft(): bool
    {
        return $this->post_status == static::STATUS_POST_DRAFT;
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
    public function getPostTypePlural(): string
    {
        return str_plural($this->getPostType());
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

    public function postFormats()
    {
        return $this->getTaxonomyRelationship($this->getLocalizedModel('PostFormat'));
    }

    public function getPostFormatAttribute()
    {
        return optional($this->getPostFormat())->getName();
    }

    public function getPostFormat()
    {
        return $this->postFormats->first();
    }

    /**
     * @return null|string
     */
    public function getAnchorAttribute(): ?string
    {
        return $this->post_title;
    }

    /**
     * @return null|string
     */
    public function getUrlAttribute(): ?string
    {
        $class = (new UrlGeneratorResolver())->resolve($this->post_type);

        return (new $class($this))->get();
    }

    /**
     * @return string
     */
    public function getClassesAttribute(): string
    {
        $classes = $this->meta->_menu_item_classes;

        if (empty($classes)) {
            return '';
        }

        return collect($classes)->implode(' ');
    }

    /**
     * @return string
     */
    public function getBodyAttribute()
    {
        return WordPressPostContentFormatter::format($this->post_content);
    }

    /**
     * @return mixed|string
     */
    public function getExcerptAttribute()
    {
        if ($this->post_excerpt) {
            return $this->post_excerpt;
        }

        return Str::limit(
            $this->post_content,
            config('wordpress.excerpt_length', 300)
        );
    }

    /**
     * @param $query
     */
    public function scopeRecent($query)
    {
        $query->latest('post_date')->published();
    }

    /**
     * @return bool
     */
    public function isPublished(): bool
    {
        return $this->post_status === static::STATUS_POST_PUBLISHED;
    }
}
