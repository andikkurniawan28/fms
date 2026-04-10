<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

    protected $guarded = [];

    public static function defineGroup($sub)
    {
        if($sub == "Aset Lancar" or $sub == "Aset Tetap") {
            return "Aset";
        } else if($sub == "Kewajiban Jangka Pendek") {
            return "Kewajiban";
        } else if($sub == "Modal") {
            return "Modal";
        } else if($sub == "Pendapatan Usaha" or $sub == "Pendapatan Lain-lain"){
            return "Pendapatan";
        } else if($sub == "Beban Operasional" or $sub == "Beban Lain-lain"){
            return "Beban";
        }
    }

    public static function defineNormalBalance($group)
    {
        if($group == "Aset" or $group == "Beban"){
            return "Debit";
        } else {
            return "Credit";
        }
    }
}
