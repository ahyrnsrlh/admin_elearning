<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ClassRoom extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
        'subject',
        'description',
        'type',
        'teacher_id',
        'schedule',
        'price',
        'is_active'
    ];
    
    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean'
    ];
    
    /**
     * Relationship to teacher
     */
    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }
    
    /**
     * Relationship to students (many-to-many)
     */
    public function students()
    {
        return $this->belongsToMany(Student::class, 'class_student')->withTimestamps();
    }
    
    /**
     * Relationship to payments
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
    
    /**
     * Check if class is bimbel type
     */
    public function isBimbel()
    {
        return $this->type === 'bimbel';
    }
    
    /**
     * Get students count
     */
    public function getStudentsCountAttribute()
    {
        return $this->students()->count();
    }
}
