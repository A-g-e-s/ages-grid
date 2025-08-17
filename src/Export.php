<?php

declare(strict_types=1);


namespace Ages\Grid;

use Ages\Grid\Column\Collection;
use Ages\Grid\Column\Column;
use Ages\Grid\Column\ColumnBoolean;
use Ages\Grid\Column\ColumnDate;
use Ages\Grid\Column\ColumnImage;
use Ages\Grid\Column\ColumnNumber;
use App\Components\Storage\Storage;
use DateTime;
use Nette\Utils\Strings;
use Nextras\Orm\Collection\ICollection;
use Nextras\Orm\Entity\IEntity;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Export
{
    const string BasePath = __DIR__ . '/../../../www/';
    const string ExportPath = self::BasePath . Storage::DirExport;

    /**
     * @param Collection<Column>  $columnCollection
     * @param ICollection<IEntity> $dataCollection
     * @param string|null $caption
     * @param int         $columnStart
     * @param int         $lineStart
     * @param string      $fontColor
     * @param string      $borderColor
     */
    public function __construct(
        private readonly Collection $columnCollection,
        private readonly ICollection $dataCollection,
        private readonly ?string $caption,
        private readonly int $columnStart = 1,
        private readonly int $lineStart = 1,
        private readonly string $fontColor = '292b2f',
        private readonly string $borderColor = '9ca3af'

    ) {
    }

    public function exportData(): string
    {
        $ss = new Spreadsheet();
        $date = (new \DateTimeImmutable())->format('d_m_Y__G_i_s');
        $title = $this->caption ?? 'Export';
        $title .= sprintf('_%s', $date);

        $ss->getProperties()
            ->setSubject($title)
            ->setTitle($title)
            ->setCreator('Grid')
            ->setLastModifiedBy('Grid');
        $s = $ss->getActiveSheet();
        $s->setTitle('Data');

        $lastCol = $this->setHeader($s);
        $lastLine = $this->setData($s);
        $this->setStyles($s, $lastCol, $lastLine);
        $writer = new Xlsx($ss);
        $name = sprintf('%s.xlsx', Strings::webalize($title));
        $writer->save(self::ExportPath . $name);
        return $name;
    }

    private function setHeader(Worksheet $sheet): int
    {
        $col = $this->columnStart;
        foreach ($this->columnCollection as $column) {
            $cord = [$col, $this->lineStart];
            $sheet->setCellValue($cord, $column->getLabel());
            $sheet->getStyle(array_merge($cord, $cord))->getAlignment()->setHorizontal($this->getAlignment($column->getAlign()));
            if ($column instanceof ColumnImage) {
                $sheet->getColumnDimensionByColumn($col)->setWidth(19);
            }
            $col++;
            if ($column->hasUnit()) {
                $cord = [$col, $this->lineStart];
                $sheet->setCellValue($cord, 'Jednotka');
                $sheet->getStyle(array_merge($cord, $cord))->getAlignment()->setHorizontal($this->getAlignment(TextAlign::Left->value));
                $col++;
            }
        }
        return --$col;
    }

    private function setData(Worksheet $sheet): int
    {
        $line = $this->lineStart + 1;
        foreach ($this->dataCollection as $entity) {
            $col = $this->columnStart;
            foreach ($this->columnCollection as $column) {
                $cord = [$col, $line];
                switch (true) {
                    case ($column instanceof ColumnBoolean):
                        $sheet->getCell($cord)->setValueExplicit($column->getColumnRawValue($entity), DataType::TYPE_BOOL);
                        break;
                    case ($column instanceof ColumnDate):
                        $rawValue = $column->getColumnRawValue($entity);
                        if ($rawValue instanceof \DateTimeImmutable) {
                            $sheet->setCellValue($cord, Date::dateTimeToExcel(DateTime::createFromImmutable($rawValue)));
                            $sheet->getStyle(array_merge($cord, $cord))->getNumberFormat()
                                ->setFormatCode(NumberFormat::FORMAT_DATE_DDMMYYYY);
                        } else {
                            $sheet->setCellValue($cord, $rawValue);
                        }
                        break;
                    case ($column instanceof ColumnNumber):
                        $sheet->setCellValue($cord, $column->getColumnRawValue($entity));
                        $sheet->getStyle(array_merge($cord, $cord))->getNumberFormat()
                            ->setFormatCode(NumberFormat::FORMAT_NUMBER_0);
                        break;
                    case ($column instanceof ColumnImage):
                        if (is_string($column->getColumnRawValue($entity))) {
                            $file = sprintf('%s%s', self::BasePath, $column->getColumnRawValue($entity));
                            if (Storage::fileExist($file)) {
                                $sheet->getRowDimension($line)->setRowHeight(60);
                                $drawing = new Drawing();
                                $drawing->setPath($file);
                                $drawing->setWidthAndHeight(120, 90);
                                $drawing->setResizeProportional(true);
                                $drawing->setCoordinates($sheet->getCell($cord)->getCoordinate());
                                $drawing->setOffsetX(5);
                                $drawing->setOffsetY(5);
                                $drawing->setWorksheet($sheet);
                            }
                        }
                        break;
                    default:
                        $sheet->setCellValue($cord, $column->getColumnRawValue($entity));
                }
                $sheet->getStyle(array_merge($cord, $cord))->getAlignment()->setHorizontal($this->getAlignment($column->getAlign()));
                $col++;
                if ($column->hasUnit()) {
                    $cord = [$col, $line];
                    $sheet->setCellValue($cord, $column->getUnit($entity));
                    $sheet->getStyle(array_merge($cord, $cord))->getAlignment()->setHorizontal($this->getAlignment(TextAlign::Left->value));
                    $col++;
                }
            }
            $line++;
        }
        return --$line;
    }

    private function setStyles(Worksheet $sheet, int $columnEnd, int $lastLine): void
    {
        $sheet->getStyle([$this->columnStart, $this->lineStart, $columnEnd, $this->lineStart])->getFont()->setBold(true);
        $sheet->getStyle([$this->columnStart, $this->lineStart, $columnEnd, $lastLine])->getFont()->setColor(new Color($this->fontColor));
        $sheet->getStyle([$this->columnStart, $this->lineStart, $columnEnd, $lastLine])->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->setColor(new Color($this->borderColor));
        $sheet->getStyle([$this->columnStart, $this->lineStart, $columnEnd, $this->lineStart])->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THICK);

        $sheet->setSelectedCell('A1');
    }

    private function getAlignment(string $align): string
    {
        return match ($align) {
            TextAlign::Right->value => Alignment::HORIZONTAL_RIGHT,
            TextAlign::Center->value => Alignment::HORIZONTAL_CENTER,
            default => Alignment::HORIZONTAL_LEFT
        };
    }
}
