<?php

namespace LaPress\Models;

/**
 * @author    Sebastian Szczepański
 * @copyright ably
 */
class TermMeta extends AbstractMeta
{
    /**
     * @var string
     */
    protected $table = 'termmeta';

    protected $fillable = [
        'meta_value',
        'meta_key',
    ];

    /**
     * Term
     * Define a relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function term()
    {
        $termClass = Term::class;
        if (class_exists('App\\Models\\Term')) {
            $termClass = 'App\\Models\\Term';
        }

        return $this->belongsTo($termClass);
    }
}

