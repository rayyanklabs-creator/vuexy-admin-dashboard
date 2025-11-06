<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Schema;

class BaseService
{
    /**
     * Common DataTables server-side processing (optimized for eager loading)
     */
    protected function processDataTables(
        Request $request,
        Builder $query,
        array $searchableColumns,
        array $orderableColumns,
        callable $formatCallback
    ) {

        $totalRecords = (clone $query)->count();
        $searchTerm = $request->input('search.value');
        if ($searchTerm && count($searchableColumns)) {
            $query->where(function ($q) use ($searchTerm, $searchableColumns) {
                foreach ($searchableColumns as $column) {
                    $q->orWhere($column, 'LIKE', "%{$searchTerm}%");
                }
            });
        }

        $totalFiltered = (clone $query)->count();

        $orderColumnIndex = (int) $request->input('order.0.column', 0);
        $orderDirection = $request->input('order.0.dir', 'asc');

        $orderColumnName = $request->input("columns.{$orderColumnIndex}.name");

        if (!empty($orderColumnName) && in_array($orderColumnName, $orderableColumns)) {
            $query->orderBy($orderColumnName, $orderDirection);
        } else {
            $query->orderBy('id', 'asc');
        }

        $limit = (int) $request->input('length', 10);
        $start = (int) $request->input('start', 0);

        $records = $query->skip($start)->take($limit)->get();

        $formattedData = [];
        $index = $start + 1;

        foreach ($records as $record) {
            $formattedData[] = $formatCallback($record, $index++);
        }

        return [
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalFiltered,
            'data' => $formattedData,
        ];
    }
}
