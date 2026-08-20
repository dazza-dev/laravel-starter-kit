<?php

declare(strict_types=1);

if (! function_exists('dataTableFilterRules')) {
    /**
     * Reglas base de paginación, búsqueda y orden en snake_case (formato que llega desde la SPA), más las del módulo.
     */
    function dataTableFilterRules(array $rules = []): array
    {
        return array_merge([
            'search' => ['nullable', 'string', 'max:255'],
            'page' => ['nullable', 'integer', 'min:1'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
            'sort_by' => ['nullable', 'array'],
            'sort_by.*.key' => ['required_with:sort_by.*', 'string'],
            'sort_by.*.order' => ['required_with:sort_by.*', 'string', 'in:asc,desc'],
        ], $rules);
    }
}

if (! function_exists('dataTableFilterAttributes')) {
    /**
     * Etiquetas de campos base combinadas con las etiquetas específicas del módulo.
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
     * Mensajes de validación base combinados con los mensajes específicos del módulo.
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
        ], $messages);
    }
}
