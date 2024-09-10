<?php

namespace App\Exports;

use App\Models\Asset;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class DataExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{

    use Exportable;


    private ?int $category_id;
    private ?int $company_id;
    private ?string $status;

    public function forCaterogry(?int $category_id): self
    {
        $this->category_id = $category_id;

        return $this;
    }

    /**
     * Filter orders by specific company_id
     */
    public function forCompany(?int $company_id): self
    {
        $this->company_id = $company_id;

        return $this;
    }

    /**
     * Filter orders by specific status
     */
    public function forStatus(?string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function query()
    {
        $q = Asset::query();
        // Validate if category_id, company_id and status are not null
        if ($this->category_id && $this->company_id && $this->status) {
            $q->where(['active' => 1, 'category_id' => $this->category_id, 'company_id' => $this->company_id, 'status' => $this->status]);
        } elseif ($this->category_id && $this->company_id) {
            $q->where(['active' => 1, 'category_id' => $this->category_id, 'company_id' => $this->company_id]);
        } elseif ($this->category_id && $this->status) {
            $q->where(['active' => 1, 'category_id' => $this->category_id, 'status' => $this->status]);
        } elseif ($this->company_id && $this->status) {
            $q->where(['active' => 1, 'company_id' => $this->company_id, 'status' => $this->status]);
        } elseif ($this->category_id) {
            $q->where(['active' => 1, 'category_id' => $this->category_id]);
        } elseif ($this->company_id) {
            $q->where(['active' => 1, 'company_id' => $this->company_id]);
        } elseif ($this->status) {
            $q->where(['active' => 1, 'status' => $this->status]);
        } else {
            $q->where('active', 1);
        }
        return $q;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Código',
            'Nombre',
            'Categoría',
            'Empresa',
            'Sede',
            'Cantidad',
            'Área',
            'Marca',
            'Modelo',
            'Serie',
            'Estado',
            'Funcionamiento',
            'Rendimiento',
            'Edad',
            'Vida útil',
            'VR. Nuevo',
            '%Salv',
            '1-R',
            'E/V',
            'B*C',
            '1-D',
            'VR. Depresiado',
        ];
    }

    public function map($asset): array
    {
        $salv = $asset->performance / 1000;
        $OneR = number_format(1 - $salv, 2);
        $EV = $asset->age / $asset->usefulLife;
        $BC = number_format((1 - $salv) * $EV, 4);
        $OneD = number_format(1 - $BC, 4);
        $VA = number_format($asset->amount * $OneD, 0);
        if ($asset->headquarter) {
            $headquarter = $asset->headquarter->name;
        } else {
            $headquarter = 'N/A';
        }
        return [
            $asset->id,
            $asset->code,
            $asset->name,
            $asset->category->name,
            $asset->company->name,
            $headquarter,
            $asset->quantity,
            $asset->area,
            $asset->brand,
            $asset->model,
            $asset->serie,
            $asset->state,
            $asset->status,
            $asset->performance,
            $asset->age,
            $asset->usefulLife,
            $asset->amount,
            $salv,
            $OneR,
            $EV,
            $BC,
            $OneD,
            $VA,
        ];
    }
}
