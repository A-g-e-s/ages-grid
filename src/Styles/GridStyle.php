<?php

namespace Ages\Grid\Styles;

class GridStyle implements GridStyleInterface
{
    public function actionColumnName(): string
    {
        return 'Akce';
    }

    public function iconSize(): string
    {
        return 'size-5';
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
        return 'inline-flex items-center px-2 py-1 rounded-xl no-underline text-sm text-txt-blue hover:text-txt-orange';
    }

    public function mainContainer(): string
    {
        return 'text-neutral-800 overflow-x-auto';
    }

    public function captionContainer(): string
    {
        return 'mb-2 rounded-full text-lg flex items-center border-brand-orange border drop-shadow-md shadow-md';
    }

    public function caption(): string
    {
        return 'rounded-l-full py-2 bg-brand-orange px-4 font-semibold';
    }

    public function headerAction(): string
    {
        return 'inline-flex items-center px-3 py-1 no-underline hover:underline text-txt-blue hover:text-txt-red';
    }

    public function table(): string
    {
        return 'mt-0 w-full border-collapse dataGrid table-auto';
    }

    public function headRow(): string
    {
        return 'sticky top-0 bg-bg-main';
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
        return 'no-underline hover:underline text-txt-blue hover:text-txt-red';
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
        return 'font-normal w-full shadow border rounded-md border-neutral-300 focus:outline-none focus:shadow-outline focus:ring-2 py-1 px-3 mb-2  ring-txt-orange';
    }

    public function bodyRow(): string
    {
        return '';
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
        return 'inline-flex items-center p-1 rounded-xl no-underline hover:text-txt-orange hover:underline';
    }

    public function actionDeleteContainer(): string
    {
        return 'inline-flex items-center p-1 rounded-xl no-underline hover:text-txt-orange hover:underline';
    }

    public function actionDeleteButton(): string
    {
        return 'p-1 hover:text-txt-orange hover:underline';
    }

    public function deleteButtonsContainer(): string
    {
        return 'flex items-center space-x-3 p-1';
    }

    public function deleteButton(): string
    {
        return 'text-txt-red hover:text-txt-orange';
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
        return 'no-underline text-txt-pink hover:underline';
    }

    public function boolSuccess(): string
    {
        return '';
    }

    public function boolDanger(): string
    {
        return '';
    }

    public function boolNeutral(): string
    {
        return '';
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
        return 'inline-flex no-underline text-txt-pink hover:underline';
    }
}
