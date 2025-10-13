<?php

namespace Ro749\ListingUtils\ImageMapPro;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MultiFloorImageMapPro extends ImageMapProBase
{
    public string $floor_column;
    public string $type_column;
    public string $data_column;
    public array $floors = [];

    public array $files;

    public string $floors_table = 'floors';

    public function __construct(
        string $table,
        string $floor_column,
        string $type_column,
        string $data_column,
        array $files,
        array $colors,
        array $opacities,
        string $status_column = 'status',
        string $floors_table = 'floors'
    ){
        parent::__construct(
            table:$table, 
            colors: $colors,
            opacities: $opacities
        );
        $this->floor_column = $floor_column;
        $this->type_column = $type_column;
        $this->data_column = $data_column;
        $this->files = $files;
        $this->floors_table = $floors_table;
    }

    public function get_tower_map(){
        $path = storage_path($this->files[0]);
        $map = json_decode(file_get_contents($path),true);
        $data = DB::table($this->table)
            ->select($this->floor_column)
            ->selectRaw("
                CASE 
                    WHEN MIN(".$this->data_column.") >= 1 AND MAX(".$this->data_column.") <= 2 
                    THEN 1 
                    ELSE 0 
                END as ".$this->data_column."
            ")
            ->groupBy($this->floor_column)
            ->get();
        $dispo = [];
        foreach($data as $d){
            Log::debug(json_encode($d));
            $dispo[$d->{$this->floor_column}] = $d->status;
        }
        return $this->re_color($map, $dispo);
    }

    public function get_floor_map($floor){
        $map_id = DB::table($this->floors_table)->where('floor',$floor)->value('map');
        $path = storage_path($this->files[$map_id]);
        $map = json_decode(file_get_contents($path),true);
        $data = DB::table($this->table)
        ->select('id',$this->type_column,$this->data_column)
        ->where($this->floor_column,$floor)
        ->get();
        $dispo = [];
        foreach($data as $d){
            $dispo[$d->{$this->type_column}] = $d->{$this->status_column};
        }
        return $this->re_color($map, $dispo);
    }

    function get_unit(Request $data){
        $data = DB::table($this->table)->
        where($this->floor_column,$data->input("floor"))->
        where($this->type_column,$data->input("type"))->
        first();
        if($data->status != 0){
            return null;
        }
        return $data;
    }
}