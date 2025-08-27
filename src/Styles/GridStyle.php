<?php

namespace Ages\Grid\Styles;

class GridStyle implements GridStyleInterface
{
    public function actionColumnName(): string
    {
        return 'Akce';
    }

    public function paginatorSummaryText(): string
    {
        return 'Celkem:';
    }

    public function iconSize(): string
    {
        return 'size-6';
    }

    public function ajax(): string
    {
        return '';
    }

    public function noAjax(): string
    {
        return 'noAjax';
    }

    public function mainExportMode(): string
    {
        return 'inline-flex items-center px-2 py-1 rounded-xl no-underline text-sm text-blue-800 hover:text-orange-500';
    }

    public function mainContainer(): string
    {
        return 'text-neutral-800 overflow-x-auto';
    }

    public function captionContainer(): string
    {
        return 'mb-2 rounded-full text-lg flex items-center border-orange-500 border drop-shadow-md shadow-md';
    }

    public function caption(): string
    {
        return 'rounded-l-full py-2 bg-orange-500 px-4 font-semibold';
    }

    public function headerAction(): string
    {
        return 'inline-flex items-center px-3 py-1 no-underline hover:underline text-sky-800 hover:text-rose-800';
    }

    public function form(): string
    {
        return 'overflow-x-auto';
    }

    public function table(): string
    {
        return 'mt-0 w-full border-collapse table-auto';
    }

    public function headRow(): string
    {
        return 'sticky top-0';
    }

    public function headColumn(): string
    {
        return 'border-b-2 p-2 pb-1 font-semibold border-neutral-300';
    }

    public function headColumnWithFilter(): string
    {
        return 'border-b p-2 pb-1 font-semibold border-neutral-300';
    }

    public function headLink(): string
    {
        return 'no-underline hover:underline text-sky-800 hover:text-rose-800';
    }

    public function filterRow(): string
    {
        return '';
    }

    public function filterColumn(): string
    {
        return 'px-2 py-2 border-b-2 border-neutral-300';
    }

    public function filterInput(): string
    {
        return 'font-normal w-full shadow border rounded-md border-neutral-300 focus:outline-none focus:shadow-outline focus:ring-2 py-1 px-3 mb-2  ring-orange-500';
    }

    public function bodyRow(): string
    {
        return 'even:text-neutral-500';
    }

    public function bodyRowHover(): string
    {
        return 'hover:bg-indigo-50';
    }

    public function bodyColumn(): string
    {
        return 'px-2 py-2 border-b border-neutral-300';
    }

    public function actionContainer(): string
    {
        return 'flex items-center justify-end gap-2';
    }

    public function actionLink(): string
    {
        return 'inline-flex items-center p-1 rounded-xl no-underline hover:text-orange-500 hover:underline';
    }

    public function actionDeleteContainer(): string
    {
        return 'inline-flex items-center p-1 rounded-xl no-underline hover:text-orange-500 hover:underline';
    }

    public function actionDeleteButton(): string
    {
        return 'p-1 text-red-500 hover:text-red-600 hover:underline';
    }

    public function deleteButtonsContainer(): string
    {
        return 'flex items-center space-x-3 p-1';
    }

    public function deleteButton(): string
    {
        return 'text-red-500 hover:text-orange-500';
    }

    public function cancelButton(): string
    {
        return 'text-neutral-500 hover:text-neutral-800';
    }

    public function summaryRow(): string
    {
        return '';
    }

    public function summaryColumn(): string
    {
        return 'px-2 py-2 border-t-2 text-left border-neutral-300';
    }

    public function columnLink(): string
    {
        return 'no-underline text-rose-400 hover:underline';
    }

    public function boolSuccess(): string
    {
        return 'text-green-600';
    }

    public function boolDanger(): string
    {
        return 'text-red-600';
    }

    public function boolNeutral(): string
    {
        return 'text-neutral-600';
    }

    public function imgSmall(): string
    {
        return 'object-scale-down h-8 w-20';
    }

    public function imgMedium(): string
    {
        return 'object-scale-down h-12 w-28';
    }

    public function imgBig(): string
    {
        return 'object-scale-down h-16 w-32';
    }

    public function fileAction(): string
    {
        return 'inline-flex no-underline text-rose-400 hover:underline';
    }

    public function paginatorContainer(): string
    {
        return 'mt-2 text-neutral-900';
    }

    public function paginatorIconSize(): string
    {
        return 'size-6';
    }

    public function paginatorPagesContainer(): string
    {
        return 'relative z-0 inline-flex -space-x-px mb-2 rounded-full bg-neutral-200';
    }

    public function paginatorOptionsContainer(): string
    {
        return 'relative z-0 md:float-right -space-x-px inline-flex mb-2 rounded-full bg-neutral-200';
    }

    public function paginatorIconLink(): string
    {
        return 'relative inline-flex items-center px-2 py-2 outline-none hover:text-rose-800';
    }

    public function paginatorLink(): string
    {
        return 'px-4 py-2 no-underline outline-none hover:text-rose-800';
    }

    public function paginatorActual(): string
    {
        return 'px-4 py-2 text-blue-800 font-semibold';
    }

    public function paginatorText(): string
    {
        return 'px-4 py-2 text-blue-800 font-semibold';
    }

    public function paginatorSummaryContainer(): string
    {
        return 'relative inline-flex items-center px-2 pr-4 rounded-full bg-neutral-200';
    }

    public function paginatorSummaryIconContainer(): string
    {
        return 'relative inline-flex items-center px-2 py-2 md:ml-4 text-neutral-700';
    }

    public function paginatorSummary(): string
    {
        return 'font-semibold pr-2';
    }
}
