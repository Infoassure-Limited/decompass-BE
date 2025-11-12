<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PolicyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $today = date('Y-m-d', strtotime('now'));
        $due_for_review = 0;
        if ($this->review_date <= $today && $this->status == 'published') {
            $due_for_review = 1;
        }
        if ($this->status == 'review') {
            $due_for_review = 1;
        }
        return [
            'id' => $this->id,
            'title' => $this->title,
            'document_number' => $this->document_number,
            'content' => $this->content,
            'status' => $this->status,
            'effective_date' => $this->effective_date,
            'review_date' => $this->review_date,
            'expiry_date' => $this->expiry_date,
            'review_interval' => $this->review_interval,
            'next_review_date' => $this->next_review_date,
            'due_for_review' => $due_for_review,
            'published_at' => $this->published_at,
            'approved_at' => $this->approved_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'category' => new PolicyCategoryResource($this->whenLoaded('category')),
            'owner' => new UserResource($this->whenLoaded('owner')),
            'approver' => new UserResource($this->whenLoaded('approver')),
            'versions' => PolicyVersionResource::collection($this->whenLoaded('versions')),
            'user_has_read' => $this->whenLoaded('users', function () use ($request) {
                $user = $request->user();
                $userPivot = $this->users->where('id', $user->id)->first();
                return $userPivot ? $userPivot->pivot->read_at !== null : false;
            }),
            'user_has_acknowledged' => $this->whenLoaded('users', function () use ($request) {
                $user = $request->user();
                $userPivot = $this->users->where('id', $user->id)->first();
                return $userPivot ? $userPivot->pivot->acknowledged_at !== null : false;
            }),
        ];
    }
}
