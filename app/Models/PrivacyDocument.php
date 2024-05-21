<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrivacyDocument extends Model
{
    use HasFactory;
    protected $table = 'privacy_documents';
    protected $fillable = ['document_name', 'file_path'];
}
