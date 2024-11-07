<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Employee;
use App\Models\Leave;
use App\Models\Role;
use App\Models\Task;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
    protected function name(): Attribute
    {

        return Attribute::make(

            get: fn($value) => ucFirst($value),
            set: fn($value) => strtolower($value),
        );
    }
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    public function hasRole($role)
    {
        $roles  =   $this->roles()->pluck('name')->toArray();
        $roles  = array_map('strtolower',$roles);
        if(in_array(strtolower($role),$roles))
        {
            return true;
        }
        return false;
    }
    public function hasAnyRole(array $roles)
    {
        $userRoles   =   $this->roles()->pluck('name')->toArray();
        $userRoles   = array_map('strtolower',$userRoles );
        $roles = array_map('strtolower', $roles);

        return (bool) array_intersect($userRoles, $roles);
    }
    public function employee():HasOne
    {
        return $this->hasOne(Employee::class);
    }
    public function tasks():HasMany
    {
        return $this->hasMany(Task::class);
    }
    public function leaves():HasMany
    {
        return $this->hasMany(Leave::class);
    }
    public function attendance():HasMany
    {
        return $this->hasMany(Attendance::class);
    }
    public function hasPermission($access,$module)
    {
        if($this->hasRole('admin'))
        {
            return true;
        }
        if($this->permissionCache == null){
            $this->permissionCache  = $this->permissions();
        }
        if(Module::$moduleCache == null){
            Module::$moduleCache = Module::all();
        }
        $module = Module::$moduleCache->where('name', $module)->first();
        if( $this->permissionCache->isNotEmpty() && !empty($module))
        {
            $permissions  = $this->permissionCache->where('module_id',$module->id);

            if($permissions->isNotEmpty())
            {
                $permissions = $permissions->where('name',$access);
                if($permissions->isNotEmpty() )
                {
                    return true;
                }
            }
        }
        return false;
    }
    public function permissions()
    {
        return $this->roles->load('permissions')->pluck('permissions')->collapse()->map(function($item){
            $item->access = strtolower($item->access);
            return $item;
        });
    }
}


