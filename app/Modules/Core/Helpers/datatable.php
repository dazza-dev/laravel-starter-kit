<?php

declare(strict_types=1);

if (! function_exists('dataTableFilterRules')) {
    /**
     * Base pagination, search and sort rules in snake_case (the SPA's wire format), plus the module's own.
     */
    function dataTableFilterRules(array $rules = []): array
    {
        return array_merge([
            'search' => ['nullable', 'string', 'max:255'],
            'page' => ['nullable', 'integer', 'min:1'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
            'sort_by' => ['nullable', 'array'],
            // Both optional: a missing or meaningless key falls back to the default order, never a 422.
            'sort_by.*.key' => ['nullable', 'string'],
            'sort_by.*.order' => ['nullable', 'string', 'in:asc,desc'],
        ], $rules);
    }
}

if (! function_exists('dataTableSort')) {
    /**
     * Resolves the SPA's requested sort against the $sortable allowlist; an unknown key falls back to the default order.
     */
    function dataTableSort(array $sortBy, array $sortable, string $defaultKey, string $defaultOrder = 'asc'): array
    {
        $sort = $sortBy[0] ?? null;
        $key = $sort['key'] ?? null;

        if (! $key || ! array_key_exists($key, $sortable)) {
            return ['column' => $sortable[$defaultKey], 'order' => $defaultOrder];
        }

        return [
            'column' => $sortable[$key],
            'order' => ($sort['order'] ?? 'asc') === 'desc' ? 'desc' : 'asc',
        ];
    }
}

if (! function_exists('dataTableFilterAttributes')) {
    /**
     * Base field labels combined with the module's own.
     */
    function dataTableFilterAttributes(array $attributes = []): array
    {
        return array_merge([
            'search' => __('core::validation.attributes.search'),
            'page' => __('core::validation.attributes.page'),
            'per_page' => __('core::validation.attributes.per_page'),
            'sort_by' => __('core::validation.attributes.sort_by'),
        ], $attributes);
    }
}

if (! function_exists('dataTableFilterMessages')) {
    /**
     * Base validation messages combined with the module's own.
     */
    function dataTableFilterMessages(array $messages = []): array
    {
        return array_merge([
            'search.max' => __('core::validation.search.max'),
            'page.integer' => __('core::validation.page.integer'),
            'page.min' => __('core::validation.page.min'),
            'per_page.integer' => __('core::validation.per_page.integer'),
            'per_page.min' => __('core::validation.per_page.min'),
            'per_page.max' => __('core::validation.per_page.max'),
            'sort_by.*.order.in' => __('core::validation.sort_by.order'),
        ], $messages);
    }
}
