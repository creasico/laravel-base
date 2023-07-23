<?php

namespace Creasi\Base\Http\Resources;

use Creasi\Base\Models\Enums\Education;
use Creasi\Base\Models\Enums\TaxStatus;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \Creasi\Base\Models\User
 */
class ProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        $resource = [
            $this->getKeyName() => $this->getKey(),
            'avatar' => null,
            'fullname' => $this->identity->name,
            'nickname' => $this->identity->alias,
            'username' => $this->name,
            'email' => $this->identity->email,
            'phone' => $this->identity->phone,
            'gender' => [
                'value' => $this->identity->gender?->value,
                'label' => $this->identity->gender?->label(),
            ],
            'summary' => $this->identity->summary,
        ];

        if ($avatar = $this->identity->avatar) {
            $resource['avatar'] = [
                'url' => $avatar->url,
                'alt' => $avatar->title,
            ];
        }

        if ($profile = $this->identity->profile) {
            $resource['nik'] = $profile->nik;
            $resource['prefix'] = $profile->prefix;
            $resource['suffix'] = $profile->suffix;
            $resource['birth_date'] = $profile->birth_date;
            $resource['birth_place'] = [
                'name' => $profile->birthPlace->name,
                'code' => $profile->birthPlace->code,
            ];
            $resource['education'] = $profile->education?->value;
            $resource['religion'] = [
                'value' => $profile->religion?->value,
                'label' => $profile->religion?->label(),
            ];
            $resource['tax_status'] = [
                'value' => $profile->tax_status?->value,
                'label' => $profile->tax_status?->label(),
            ];
            $resource['tax_id'] = $profile->tax_id;
        }

        $this->additional([
            'meta' => [
                'educations' => Education::toOptions(),
                'tax_statuses' => TaxStatus::toOptions(),
            ],
        ]);

        return $resource;
    }
}
