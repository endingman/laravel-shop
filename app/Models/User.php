<?php

namespace App;namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'email_verified',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified' => 'boolean',
    ];

    public function addresses()
    {
        return $this->hasMany(UserAddress::class);
    }

    public function favoriteProducts()
    {
        /**
         * belongsToMany() 方法用于定义一个多对多的关联，第一个参数是关联的模型类名，第二个参数是中间表的表名。
         * withTimestamps() 代表中间表带有时间戳字段。
         * orderBy('user_favorite_products.created_at', 'desc') 代表默认的排序方式是根据中间表的创建时间倒序排序。
         */

        return $this->belongsToMany(Product::class, 'user_favorite_products')
            ->withTimestamps()
            ->orderBy('user_favorite_products.created_at', 'desc');
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }
}
