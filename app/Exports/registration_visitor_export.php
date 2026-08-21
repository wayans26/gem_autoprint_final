<?php

namespace App\Exports;

use App\Models\registration_visitor;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithCustomChunkSize;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class registration_visitor_export extends DefaultValueBinder implements FromQuery, WithColumnWidths, WithCustomChunkSize, WithCustomValueBinder, WithEvents, WithHeadings, WithMapping, WithTitle
{
    use RegistersEventListeners;

    protected const FIELD_LABEL_OVERRIDES = [
        'exhibition_name'                           => 'Exhibition Name',
        'sub_exhibition_name'                       => 'Sub Exhibition Name',
        'name_title'                                => 'Title',
        'business_type'                             => 'Type of Business',
        'event_find'                                => 'How Did You Find Out About This Event',
        'event_find_other'                          => 'Other Event Source',
        'is_received_invitation_next'               => 'Receive Invitation Next Year',
        'is_received_invitation_next_address_same'  => 'Invitation Address Same',
        'received_invitation_next_address'          => 'Invitation Address',
        'is_receive_news_letter'                    => 'Receive Newsletter',
        'is_agree_policy'                           => 'Agree to Policy',
        'departement'                               => 'Department',
        'departement_other'                         => 'Other Department',
    ];

    protected const BOOLEAN_FIELDS = [
        'is_received_invitation_next',
        'is_received_invitation_next_address_same',
        'is_printed',
        'is_receive_news_letter',
        'is_agree_policy',
    ];

    protected $selected_fields;
    protected $filters;

    public function __construct(array $selected_fields, array $filters)
    {
        $this->selected_fields = self::normalizeSelectedFields($selected_fields);
        $this->filters = $filters;

        if (sizeof($this->selected_fields) === 0) {
            throw new \InvalidArgumentException('No valid export field selected.');
        }
    }

    public static function getExportFieldOptions(): array
    {
        $fillable = (new registration_visitor())->getFillable();
        $fields = [
            'exhibition_name',
            'sub_exhibition_name',
        ];
        $options = [];

        foreach ($fillable as $field) {
            if ($field === 'sub_exhibitions_id') {
                continue;
            }

            array_push($fields, $field);
        }

        foreach ($fields as $field) {
            array_push($options, [
                'value' => $field,
                'label' => self::getFieldLabel($field),
            ]);
        }

        return $options;
    }

    public static function getAllowedFields(): array
    {
        return array_column(self::getExportFieldOptions(), 'value');
    }

    public static function normalizeSelectedFields(array $selected_fields): array
    {
        $normalized_fields = [];

        foreach ($selected_fields as $field) {
            if ($field === 'sub_exhibitions_id') {
                array_push($normalized_fields, 'exhibition_name', 'sub_exhibition_name');
                continue;
            }

            array_push($normalized_fields, $field);
        }

        $selected_fields = $normalized_fields;
        $allowed_fields = array_flip(self::getAllowedFields());
        $selected_fields = array_values(array_unique(array_filter($selected_fields, function ($field) use ($allowed_fields) {
            return is_string($field) && isset($allowed_fields[$field]);
        })));
        $relation_fields = [];

        foreach ([
            'exhibition_name',
            'sub_exhibition_name',
        ] as $field) {
            if (in_array($field, $selected_fields, true)) {
                array_push($relation_fields, $field);
            }
        }

        $selected_fields = array_values(array_filter($selected_fields, function ($field) {
            return !in_array($field, [
                'exhibition_name',
                'sub_exhibition_name',
            ], true);
        }));

        return array_merge($relation_fields, $selected_fields);
    }

    public static function getFieldLabel(string $field): string
    {
        return self::FIELD_LABEL_OVERRIDES[$field] ?? Str::headline($field);
    }

    public function query(): Builder
    {
        $query = registration_visitor::query()
            ->join('sub_exhibitions', 'sub_exhibitions.id', '=', 'registration_visitors.sub_exhibitions_id')
            ->join('exhibitions', 'exhibitions.id', '=', 'sub_exhibitions.exhibitions_id')
            ->when(($this->filters['status'] ?? 'all') !== 'all', function ($query) {
                return $query->where('exhibitions.status', $this->filters['status']);
            })
            ->when(sizeof($this->filters['exhibition_ids'] ?? []) > 0, function ($query) {
                return $query->whereIn('exhibitions.id', $this->filters['exhibition_ids']);
            })
            ->when(!empty($this->filters['start_date']), function ($query) {
                return $query->whereDate(
                    DB::raw('COALESCE(registration_visitors.register_date, registration_visitors.created_at)'),
                    '>=',
                    $this->filters['start_date']
                );
            })
            ->when(!empty($this->filters['end_date']), function ($query) {
                return $query->whereDate(
                    DB::raw('COALESCE(registration_visitors.register_date, registration_visitors.created_at)'),
                    '<=',
                    $this->filters['end_date']
                );
            })
            ->orderBy('registration_visitors.id', 'asc');

        $columns = array_map(function ($field) {
            if ($field === 'exhibition_name') {
                return 'exhibitions.name as exhibition_name';
            }

            if ($field === 'sub_exhibition_name') {
                return 'sub_exhibitions.name as sub_exhibition_name';
            }

            if ($field === 'register_date') {
                return DB::raw('COALESCE(registration_visitors.register_date, registration_visitors.created_at) as register_date');
            }

            return 'registration_visitors.' . $field . ' as ' . $field;
        }, $this->selected_fields);

        return $query->select($columns);
    }

    public function headings(): array
    {
        return array_map(function ($field) {
            return self::getFieldLabel($field);
        }, $this->selected_fields);
    }

    public function map($row): array
    {
        return array_map(function ($field) use ($row) {
            $value = $row->{$field};

            if (in_array($field, self::BOOLEAN_FIELDS, true)) {
                if ($value === null || $value === '') {
                    return '';
                }

                return (string) $value === '1' ? 'Yes' : 'No';
            }

            return $value === null ? '' : (string) $value;
        }, $this->selected_fields);
    }

    public function bindValue(Cell $cell, $value)
    {
        if (is_string($value)) {
            $cell->setValueExplicit($value, DataType::TYPE_STRING);
            return true;
        }

        return parent::bindValue($cell, $value);
    }

    public function columnWidths(): array
    {
        $widths = [];

        foreach ($this->selected_fields as $key => $field) {
            $column = Coordinate::stringFromColumnIndex($key + 1);
            $widths[$column] = in_array($field, [
                'company',
                'address',
                'job_function_other',
                'visit_purpose_other',
                'purchasing_role_other',
                'event_find_other',
                'received_invitation_next_address',
                'line_of_business',
            ], true) ? 35 : 22;
        }

        return $widths;
    }

    public function chunkSize(): int
    {
        return 500;
    }

    public static function afterSheet(AfterSheet $event)
    {
        $sheet = $event->sheet->getDelegate();
        $highest_column = $sheet->getHighestColumn();

        $sheet->freezePane('A2');
        $sheet->setAutoFilter('A1:' . $highest_column . '1');
        $sheet->getStyle('A1:' . $highest_column . '1')->applyFromArray([
            'font' => [
                'bold'  => true,
                'color' => [
                    'argb' => 'FFFFFFFF',
                ],
            ],
            'fill' => [
                'fillType'   => Fill::FILL_SOLID,
                'startColor' => [
                    'argb' => 'FF1D4ED8',
                ],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical'   => Alignment::VERTICAL_CENTER,
                'wrapText'   => true,
            ],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(30);
        $sheet->setShowGridlines(false);
    }

    public function title(): string
    {
        return 'Visitor Report';
    }
}
