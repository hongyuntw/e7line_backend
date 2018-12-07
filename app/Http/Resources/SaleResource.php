<?php

namespace App\Http\Resources;

use App\Sale;
use Illuminate\Http\Resources\Json\JsonResource;

class SaleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $sale =Sale::find($this->id);
        $saleitems = $sale->saleitems;
        $totalprice=0;
        foreach ($saleitems as $saleitem){
            $totalprice += $saleitem->sale_price*$saleitem->quantity;
        }
        return [
            'id' => $this->id,
            'member_id' => $this->member_id,
            'order_name' => $this->order_name,
            'order_phone' => $this->order_phone,
            'order_note' => $this->order_note,
            'order_address' => $this->order_address,
            'order_date' => $this->order_date,
            'shipment'=>$this->shipment,
            'totalprice'=> $totalprice,
            'created_at' => $this->created_at->format('Y/m/d H:i:s'),
            'updated_at' => $this->updated_at->format('Y/m/d H:i:s'),

        ];
    }
}
