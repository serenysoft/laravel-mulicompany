<?php

declare(strict_types=1);

namespace Sereny\MultiCompany;

use Illuminate\Database\Eloquent\Model;
use Sereny\MultiCompany\Exceptions\CompanyCouldNotBeIdentifiedByIdException;

class Manager
{
    /**
     * @var null|Model
     */
    public ?Model $company = null;

    /**
     * @var bool
     */
    public bool $initialized = false;

    /**
     * @var string<class-string>
     */
    public static ?string $model = 'App\\Models\\Company';

    /**
     * Finds company by id
     */
    public function find(int|string $id): Model
    {
        return static::$model::find($id);
    }

    /**
     * Initializes the company.
     */
    public function initialize(Model|int|string $company): void
    {
        if (! is_object($company)) {
            $companyId = $company;
            $company = $this->find($companyId);

            if (! $company) {
                throw new CompanyCouldNotBeIdentifiedByIdException($companyId);
            }
        }

        if ($this->initialized) {
            $this->end();
        }

        $this->company = $company;

        $this->initialized = true;
    }

    /**
     * Gets the current company
     */
    public function current(): ?Model
    {
        return $this->company;
    }

    /**
     *
     */
    public function end(): void
    {
        if (! $this->initialized) {
            return;
        }

        $this->company = null;
    }
}
