<?php

namespace App\Http\Resources;

use App\Cart;
use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $cart = Cart::find($this->id);
        $name = $cart->product->name;
        return [
            'id' => $this->id,
            'member_id' => $this->member_id,
            //'tag_line' => str_limit($this->description, 20),
            'product_imagename' =>$cart->product->imagename,
            'product_id' => $this->product_id,
            'quantity' => $this->quantity,
            'price' => $this->price,
            'product_name' => $name,
            //'date' => $this->updated_at->format('Y/m/d'),
            'created_at' => $this->created_at->format('Y/m/d H:i:s'),
            'updated_at' => $this->updated_at->format('Y/m/d H:i:s'),
        ];
    }
}
