<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'member_id', 'detail', 'fee', 'phone', 'address'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'member_id', 'created_at', 'updated_at',
    ];

    public function getStatusAttribute($value)
    {
        $status = '未知';
        switch ($value)
        {
            case 0:
                $status = '未完成';
                break;
            case 1:
                $status = '已下單';
                break;
            case 2:
                $status = '出貨中';
                break;
            case 3:
                $status = '結案';
                break;
            case 4:
                $status = '已刪除';
                break;
        }
        return $status;
    }

    /**
     * 取得所屬的會員
     */
    public function user()
    {
        return $this->belongsTo('App\User', 'member_id');
    }
}
