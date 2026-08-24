<?php

namespace App\Filament\Pages;

use Filament\Resources\Pages\ListRecords;
use Illuminate\Contracts\Pagination\CursorPaginator;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;

/**
 * Every resource's List page should extend this instead of Filament's ListRecords directly.
 * It swaps the default length-aware pagination (which runs a COUNT query and shows page
 * numbers) for simple pagination (Previous/Next only, no count) — cheaper on large tables.
 */
abstract class BaseListRecords extends ListRecords
{
    protected function paginateTableQuery(Builder $query): Paginator|CursorPaginator
    {
        $perPage = $this->getTableRecordsPerPage();

        return $query->simplePaginate(
            perPage: $perPage === 'all' ? $query->toBase()->getCountForPagination() : $perPage,
            pageName: $this->getTablePaginationPageName(),
        );
    }
}
