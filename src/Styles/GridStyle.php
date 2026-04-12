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
        return 'noAjax h-10 px-6 inline-flex items-center justify-center rounded-full whitespace-nowrap bg-linear-to-b from-brand-orange to-amber-500 text-zinc-900 font-semibold text-sm shadow-md
                        shadow-brand-orange/35 hover:brightness-110 hover:shadow-lg hover:shadow-brand-orange/45 active:brightness-95 focus:outline-none focus:ring-2 focus:ring-brand-orange/35 transition gap-2';
    }

    public function mainContainer(): string
    {
        return 'relative mb-6 overflow-hidden rounded-xl border border-indigo-200/60 bg-white/90 backdrop-blur shadow-sm before:content-[""] before:absolute before:inset-x-0 before:top-0 before:h-1.5
                before:bg-gradient-to-r before:from-indigo-700 before:via-indigo-500 before:to-indigo-300';
    }

    public function captionContainer(): string
    {
        return 'flex items-center justify-start px-5 py-3 border-b border-gray-200/60';
    }

    public function caption(): string
    {
        return 'flex items-center font-bold gap-2 text-sm text-zinc-900 relative pl-3 before:content-[""] before:absolute before:left-0 before:top-1/2 before:-translate-y-1/2 before:w-1 before:h-4 before:bg-indigo-700 uppercase';
    }

    public function headerAction(): string
    {
        return 'inline-flex items-center px-2 py-1 no-underline rounded-lg hover:underline text-indigo-700 hover:bg-indigo-100 gap-2 ml-2';
    }

    public function table(): string
    {
        return 'mt-2 min-w-full border-collapse table-fixed text-zinc-900';
    }

    public function headRow(): string
    {
        return 'sticky top-0';
    }

    public function headColumn(): string
    {
        return 'text-sm border-b p-2 pb-1 border-gray-400 font-normal';
    }

    public function headColumnWithFilter(): string
    {
        return 'border-b p-2 pb-1 border-gray-400 font-normal';
    }

    public function headLink(): string
    {
        return 'no-underline hover:underline text-indigo-600 hover:text-indigo-700';
    }

    public function filterRow(): string
    {
        return '';
    }

    public function filterColumn(): string
    {
        return 'p-2 border-b-2 border-gray-400';
    }

    public function filterInput(): string
    {
        return 'font-normal w-full shadow border rounded-lg border-gray-400 focus:outline-none focus:shadow-outline focus:border-indigo-400 focus:ring-2 py-1 px-3 mb-2 ring-indigo-400';
    }

    public function filterDateInput(): string
    {
        return 'font-normal w-full border rounded border-gray-400 focus:outline-none focus:border-indigo-400 focus:ring-1 py-0.5 px-1 text-xs ring-indigo-400';
    }

    public function bodyRow(): string
    {
        return 'even:text-zinc-600';
    }

    public function bodyRowHover(): string
    {
        return 'hover:bg-indigo-50';
    }

    public function bodyColumn(): string
    {
        return 'p-2 border-b border-gray-400';
    }

    public function actionContainer(): string
    {
        return 'flex items-center justify-end gap-2';
    }

    public function actionLink(): string
    {
        return 'inline-flex items-center p-1 rounded-lg no-underline text-indigo-700 hover:bg-indigo-200 gap-1';
    }

    public function actionDeleteContainer(): string
    {
        return 'inline-flex items-center p-1 rounded-lg no-underline hover:text-red-500 hover:underline';
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
        return 'text-red-500 hover:text-brand-orange';
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
        return 'py-2 border-t-2 text-left border-gray-400';
    }

    public function columnLink(): string
    {
        return 'no-underline underline hover:text-indigo-600 decoration-indigo-600 underline-offset-2';
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
        return 'm-2 text-neutral-900 flex flex-col lg:flex-row lg:justify-between items-start lg:items-center gap-3';
    }

    public function paginatorIconSize(): string
    {
        return 'size-6';
    }

    public function paginatorPagesContainer(): string
    {
        return 'inline-flex items-center gap-1 px-2 py-1 rounded-lg border border-gray-200 bg-white';
    }

    public function paginatorOptionsContainer(): string
    {
        return 'relative z-0 inline-flex items-center gap-1 rounded-lg bg-white';
    }

    public function paginatorIconLink(): string
    {
        return 'inline-flex items-center justify-center h-8 w-8 rounded text-gray-600 hover:bg-gray-50 hover:text-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-700/30 transition';
    }

    public function paginatorLink(): string
    {
        return 'inline-flex items-center justify-center h-8 min-w-8 px-2 rounded text-gray-700 no-underline hover:bg-gray-50 hover:text-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-700/30 transition';
    }

    public function paginatorActual(): string
    {
        return 'inline-flex items-center justify-center h-8 min-w-8 px-2 rounded bg-indigo-700/10 text-indigo-700 font-medium';
    }

    public function paginatorText(): string
    {
        return 'inline-flex items-center justify-center h-8 min-w-8 px-2 rounded text-gray-400';
    }

    public function paginatorSummaryContainer(): string
    {
        return 'inline-flex items-center gap-1 px-2 py-1 rounded-lg border border-gray-200 bg-white';
    }

    public function paginatorSummaryIconContainer(): string
    {
        return 'inline-flex items-center h-8 w-8 justify-center rounded text-gray-600';
    }

    public function paginatorSummary(): string
    {
        return 'font-medium text-gray-700 px-2';
    }

    public function form(): string
    {
        return 'overflow-x-auto';
    }

    public function paginatorTotalContainer(): string
    {
        return '';
    }
}
