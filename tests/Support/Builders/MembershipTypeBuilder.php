<?php

declare(strict_types=1);

namespace Tests\Support\Builders;

use Ramsey\Uuid\Uuid;
use Illuminate\Database\Eloquent\Model;

final class MembershipTypeBuilder
{
    private string $id;
    private string $organisationId;
    private string $name = 'Standard Membership';
    private string $slug;
    private string $feeAmount = '0.00';
    private string $feeCurrency = 'EUR';
    private bool $isActive = true;

    public static function forOrganisation(string $organisationId): self
    {
        return new self($organisationId);
    }

    private function __construct(string $organisationId)
    {
        $this->id = Uuid::uuid4()->toString();
        $this->organisationId = $organisationId;
        $this->slug = 'standard-' . substr(Uuid::uuid4()->toString(), 0, 8);
    }

    public function withName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function withSlug(string $slug): self
    {
        $this->slug = $slug;
        return $this;
    }

    public function withFeeAmount(string $amount): self
    {
        $this->feeAmount = $amount;
        return $this;
    }

    public function withFeeCurrency(string $currency): self
    {
        $this->feeCurrency = $currency;
        return $this;
    }

    public function persist(): MembershipTypeModel
    {
        return MembershipTypeModel::create([
            'id' => $this->id,
            'organisation_id' => $this->organisationId,
            'name' => $this->name,
            'slug' => $this->slug,
            'fee_amount' => $this->feeAmount,
            'fee_currency' => $this->feeCurrency,
            'is_active' => $this->isActive,
        ]);
    }
}

class MembershipTypeModel extends Model
{
    protected $table = 'membership_types';
    protected $fillable = [
        'id',
        'organisation_id',
        'name',
        'slug',
        'description',
        'fee_amount',
        'fee_currency',
        'duration_months',
        'requires_approval',
        'form_schema',
        'is_active',
        'sort_order',
        'created_by',
    ];
    protected $keyType = 'string';
    public $incrementing = false;
}
