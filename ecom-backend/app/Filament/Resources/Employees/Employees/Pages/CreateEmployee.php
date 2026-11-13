<?php

namespace App\Filament\Resources\Employees\Employees\Pages;

use App\Filament\Resources\Employees\Employees\EmployeeResource;
use Filament\Resources\Pages\CreateRecord;

class CreateEmployee extends CreateRecord
{
    protected static string $resource = EmployeeResource::class;
}
