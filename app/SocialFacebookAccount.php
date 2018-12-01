<?php

// SocialFacebookAccount.php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SocialFacebookAccount extends Model
{
    protected $fillable = ['member_id', 'provider_member_id', 'provider'];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}
