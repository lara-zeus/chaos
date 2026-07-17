<?php

namespace Tests\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * @property string $email
 * @property string $name
 * @property string $password
 */
class User extends Authenticatable implements FilamentUser
{
    use HasFactory;

    protected $guarded = [];

    public $timestamps = false;

    protected $table = 'users';

    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }
}
