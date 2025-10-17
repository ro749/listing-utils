<?php

namespace Ro749\ListingUtils\ImageMapPro;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SingleImageMapPro extends ImageMapProBase
{
    public string $label_column;
    public string $data_column;
    public string $file;

    public function __construct(
        string $table = 'units',
        string $label_column = 'unit',
        string $data_column = 'status',
        array $colors = ['#00ff00','#ff0000','#ffff00'],
        array $opacities = [0.4,0.4,0.4],
        string $selected_color = "#ffffff",
        string $file = 'imageMapPro.json'
    ){
        parent::__construct(
            table:$table, 
            colors: $colors,
            opacities: $opacities,
            selected_color: $selected_color
        );
        $this->label_column = $label_column;
        $this->data_column = $data_column;
        $this->file = $file;
    }

    public function get_map(){
        $path = storage_path($this->file);
        $map = json_decode(file_get_contents($path),true);
        $data = DB::table($this->table)->select('id',$this->label_column,$this->data_column)->get();
        $dispo = [];
        foreach($data as $d){
            $dispo[$d->unit] = $d->status;
        }
        $ans['map'] = $this->re_color($map, $dispo);
        $ans['selected_color'] = $this->selected_color;
        return $ans;
    }

    function get_unit(Request $data){
        $unit = (config('overrides.models.Unit'))::get("unit", $data->input("unit"));
        if($unit->status != 0){
            return null;
        }
        return $unit;
    }

    public function render(){
        return view('listing-utils::ImageMapPro.image-map-pro', $this);
    }
}