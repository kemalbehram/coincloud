<?php

namespace App\Models;

use Bavix\Wallet\Interfaces\Wallet;
use Bavix\Wallet\Interfaces\WalletFloat;
use Bavix\Wallet\Traits\HasWallet;
use Bavix\Wallet\Traits\HasWalletFloat;
use Bavix\Wallet\Traits\HasWallets;
use DateTimeInterface;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Overtrue\EasySms\PhoneNumber;

class User extends Authenticatable implements Wallet, WalletFloat
{
    use HasFactory, Notifiable;
    use HasApiTokens;
    use SoftDeletes;
    use HasWallet, HasWallets;
    use HasWalletFloat;

    // 钱包列表
    public const WALLETSLIST = [
        ['name' => '余额', 'slug' => 'money', 'decimal_places' => '2',],
        ['name' => '积分', 'slug' => 'credit', 'decimal_places' => '2',],
        ['name' => 'Tether 钱包', 'slug' => 'USDT', 'decimal_places' => '5',],
        ['name' => 'Filecoin 钱包', 'slug' => 'FIL', 'decimal_places' => '5',],
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'mobile', 'nickname', 'parent_id', 'status', 'last_login_at', 'last_login_ip',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // 关联 订单
//    public function order()
//    {
//        return $this->hasMany(Order::class);
//    }

    // 用户总数
    public static function count()
    {
        return self::all()->count();
    }

    // 推荐下级列表
    public function sons()
    {
        return $this->hasMany(User::class, 'parent_id', 'id');
    }

    /**
     * 获取登录用户手机号码
     * @param $notification
     * @return PhoneNumber
     */
    public function routeNotificationForEasySms($notification)
    {
        return new PhoneNumber($this->mobile);
    }

    /**
     * Passport 登录支持 邮箱 和 手机号码
     * @param $username
     * @return mixed
     */
    public function findForPassport($username)
    {
        filter_var($username, FILTER_VALIDATE_EMAIL) ?
            $credentials['email'] = $username :
            $credentials['mobile'] = $username;

        return self::where($credentials)->first();
    }

    /**
     * 为数组 / JSON 序列化准备日期。
     *
     * @param \DateTimeInterface $date
     * @return string
     */
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format($this->dateFormat ?: 'Y-m-d H:i:s');
    }
}
