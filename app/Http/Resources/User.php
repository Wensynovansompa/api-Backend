<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class User extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    // public function toArray($request)
    // {
    //     return [
    //         'status'  => 'success',
    //         'message' => 'users data',
    //         'data'    => parent::toArray($request),
    //     ];
    //     // return parent::toArray($request);
    // }

    public function toArray($request)
    {
        $parent = parent::toArray($request);
        $data['users'] = $this->users()->paginate(10);
        $data = array_merge($parent, $data);
        return [
            'status'    => 'success',
            'message'   => 'users data',
            'data'      => $data
        ];
    }
}
