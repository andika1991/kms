<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class ViewsCountScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        // Tambahkan kolom virtual views_count ke SEMUA query Dokumen
        $builder->withCount('views'); // -> menambah select: ..., (select count(*) ...) as views_count
    }
}
