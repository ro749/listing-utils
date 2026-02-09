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

    public function __construct()
    {
        parent::__construct(
            colors: config('listing.image_map_pro.colors',['#173111','#ff0000','#ffff00']),
            opacities: config('listing.image_map_pro.opacities',[0.4,0.4,0.4]),
            selected_color: config('listing.image_map_pro.selected_color',"#ffffff"),
        );
        $this->label_column = config('listing.image_map_pro.label_column','unit');
        $this->data_column = config('listing.image_map_pro.data_column','status');
        $this->file = config('listing.image_map_pro.file','imageMapPro.json');
    }

    public function get_map(){
        $path = storage_path($this->file);
        $map = json_decode(file_get_contents($path),true);
        $data = (config('overrides.models.Unit'))::select('id',$this->label_column,$this->data_column)->get();
        $dispo = [];
        foreach($data as $d){
            $dispo[$d->{$this->label_column}] = $d->{$this->data_column};
        }
        $ans['map'] = $this->re_color($map, $dispo);
        $ans['selected_color'] = $this->selected_color;
        return $ans;
    }

    function get_unit(Request $data){
        $data_class = config('overrides.data.UnitData');
        $data = new $data_class($this->label_column, $data->input("unit"));
        $unit = $data->get_data();
        if($unit->{$this->data_column} != 0){
            return null;
        }
        return $unit;
    }

    public function render(){
        return view('listing-utils::ImageMapPro.image-map-pro', ['imp' => $this]);
    }
}