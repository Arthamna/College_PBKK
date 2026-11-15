<?php

declare(strict_types=1);

namespace App\Models;

use CodeIgniter\Model;

class CategoryModel extends Model
{
    protected $table         = 'categories';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $useTimestamps = true;
    protected $allowedFields = ['name', 'slug', 'description'];

    /** @var array<string, string> */
    protected $validationRules = [
        'name'        => 'required|min_length[2]|max_length[100]',
        'description' => 'permit_empty|max_length[500]',
    ];

    /**
     * @return list<array<string, mixed>>
     */
    public function withProductCount(): array
    {
        return $this->select('categories.*, COUNT(products.id) AS product_count')
            ->join('products', 'products.category_id = categories.id', 'left')
            ->groupBy('categories.id')
            ->orderBy('categories.name')
            ->findAll();
    }

    public function slugExists(string $slug, ?int $exceptId = null): bool
    {
        $builder = $this->where('slug', $slug);

        if ($exceptId !== null) {
            $builder->where('id !=', $exceptId);
        }

        return $builder->countAllResults() > 0;
    }
}
