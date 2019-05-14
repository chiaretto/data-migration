<?php

namespace Fndmiranda\DataMigration\Traits;

use Fndmiranda\DataMigration\Contracts\DataMigration as ContractDataMigration;
use Illuminate\Support\Collection;
use Fndmiranda\DataMigration\DataMigration;
use Illuminate\Support\Facades\DB;

trait HasMigrate
{
    use HasRelationships;

    /**
     * Run the data migrations.
     *
     * @param ContractDataMigration $dataMigrate
     * @return Collection
     */
    public function migrate($dataMigrate)
    {
        $collection = collect();

        return $this->status($dataMigrate)->filter(function ($value) {
            return $value['status'] == DataMigration::CREATE;
        });

//        DB::transaction(function () use ($dataMigrate, $collection) {
////            dump($this->status($dataMigrate));
//            return $this->status($dataMigrate)->filter(function ($value) {
//                return $value['status'] == DataMigration::CREATE;
//            });
//
//            foreach ($creates as $item) {
////                dump($item['data']);
////                $entity = $this->model->create($item['data']);
////                $collection->push($entity->toArray());
//            }
//        });
//
//        return $collection;
    }
}