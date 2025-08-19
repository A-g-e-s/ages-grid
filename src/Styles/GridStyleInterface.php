<?php

namespace Ages\Grid\Styles;

interface GridStyleInterface
{
    public function actionColumnName(): string;
    public function paginatorSummaryText(): string;
    public function iconSize(): string;
    public function noAjax(): string;

    public function ajax(): string;

    public function mainExportMode(): string;

    public function mainContainer(): string;

    public function captionContainer(): string;
    public function caption(): string;

    public function headerAction(): string;

    public function table(): string;

    public function headRow(): string;

    public function headColumn(): string;

    public function headColumnWithFilter(): string;

    public function headLink(): string;

    public function filterRow(): string;

    public function filterColumn(): string;

    public function filterInput(): string;

    public function bodyRow(): string;

    public function bodyRowHover(): string;

    public function bodyColumn(): string;

    public function actionContainer(): string;

    public function actionLink(): string;

    public function actionDeleteContainer(): string;

    public function actionDeleteButton(): string;

    public function deleteButtonsContainer(): string;

    public function deleteButton(): string;
    public function cancelButton(): string;

    public function summaryRow(): string;

    public function summaryColumn(): string;

    public function columnLink(): string;

    public function boolSuccess(): string;

    public function boolDanger(): string;

    public function boolNeutral(): string;

    public function imgSmall(): string;

    public function imgMedium(): string;

    public function imgBig(): string;

    public function fileAction(): string;

    public function paginatorContainer(): string;

    public function paginatorIconSize(): string;

    public function paginatorPagesContainer(): string;

    public function paginatorOptionsContainer(): string;

    public function paginatorIconLink(): string;

    public function paginatorLink(): string;

    public function paginatorActual(): string;

    public function paginatorText(): string;

    public function paginatorSummaryContainer(): string;

    public function paginatorSummaryIconContainer(): string;

    public function paginatorSummary(): string;

}
