<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;


class Faq extends Model
{
    use HasFactory;

    protected $fillable = [
        'question',
        'answer',
        'sort_order',
        'status'
    ];

    protected $casts = [
        'status' => 'boolean',
        'sort_order' => 'integer',
    ];

    // ============ SCOPES ============
    
    /**
     * Scope a query to only include active faqs.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    /**
     * Scope a query to only include inactive faqs.
     */
    public function scopeInactive($query)
    {
        return $query->where('status', 0);
    }

    /**
     * Scope a query to order by sort_order.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order', 'asc');
    }

    // ============ ACCESSORS ============
    
    /**
     * Get the status as text.
     */
    public function getStatusTextAttribute(): string
    {
        return $this->status ? 'نشط' : 'غير نشط';
    }

    /**
     * Get the status badge HTML.
     */
    public function getStatusBadgeAttribute(): string
    {
        if ($this->status) {
            return '<span class="badge bg-success">نشط</span>';
        }
        return '<span class="badge bg-secondary">غير نشط</span>';
    }

    /**
     * Get truncated question for lists.
     */
    public function getShortQuestionAttribute(): string
    {
        return Str::limit($this->question, 60);
    }

    /**
     * Get plain text answer (without HTML).
     */
    public function getPlainAnswerAttribute(): string
    {
        return strip_tags($this->answer);
    }

    /**
     * Get short plain text answer.
     */
    public function getShortAnswerAttribute(): string
    {
        return Str::limit(strip_tags($this->answer), 120);
    }

    // ============ METHODS ============
    
    /**
     * Activate the faq.
     */
    public function activate(): bool
    {
        return $this->update(['status' => 1]);
    }

    /**
     * Deactivate the faq.
     */
    public function deactivate(): bool
    {
        return $this->update(['status' => 0]);
    }

    /**
     * Toggle the status.
     */
    public function toggleStatus(): bool
    {
        return $this->update(['status' => !$this->status]);
    }

    /**
     * Move up in sort order.
     */
    public function moveUp(): void
    {
        $previous = static::where('sort_order', '<', $this->sort_order)
            ->orderBy('sort_order', 'desc')
            ->first();

        if ($previous) {
            $temp = $this->sort_order;
            $this->sort_order = $previous->sort_order;
            $previous->sort_order = $temp;
            
            $this->save();
            $previous->save();
        }
    }

    /**
     * Move down in sort order.
     */
    public function moveDown(): void
    {
        $next = static::where('sort_order', '>', $this->sort_order)
            ->orderBy('sort_order', 'asc')
            ->first();

        if ($next) {
            $temp = $this->sort_order;
            $this->sort_order = $next->sort_order;
            $next->sort_order = $temp;
            
            $this->save();
            $next->save();
        }
    }

    /**
     * Get the next available sort order.
     */
    public static function getNextSortOrder(): int
    {
        return static::max('sort_order') + 1;
    }
}