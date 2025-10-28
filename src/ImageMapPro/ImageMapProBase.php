<?php

namespace Ro749\ListingUtils\ImageMapPro;
use Illuminate\Http\Request;
abstract class ImageMapProBase
{
    public string $status_column;
    public string $unit_class;
    public array $colors = [];
    public array $opacities = [];

    public string $selected_color = "#ffffff";

    public function __construct(
        array $colors = ['#00ff00','#ff0000','#ffff00'],
        array $opacities = [0.4,0.4,0.4],
        string $selected_color = "#ffffff",
        string $status_column = 'status'
    ){
        $this->colors = $colors;
        $this->opacities = $opacities;
        $this->selected_color = $selected_color;
        $this->status_column = $status_column;
    }

    public function no_color_unit(&$child){
        $child["default_style"]["background_color"] = "#ffffff";
        $child["default_style"]["stroke_color"] = "#ffffff";
        $child["default_style"]["background_opacity"] = 0;
        $child["default_style"]["stroke_opacity"] = 0;
        $child["default_style"]["border_opacity"] = 0;
        $child["mouseover_style"]["opacity"] = 0;
        $child["mouseover_style"]["background_opacity"] = 0;
        unset( $child["tooltip_content"]);
        unset( $child["tooltip_style"]);
    }

    public function style_unit(&$child,&$dispo){
        if(!isset($dispo[$child["title"]])){
            $this->no_color_unit($child);
            return;
        }
        $color_value = $dispo[$child["title"]];
        if(!isset($this->colors[$color_value])){
            $this->no_color_unit($child);
            return;
        }
        $color = $this->colors[$color_value];
        $opacity = $this->opacities[$color_value];
        $child["default_style"]["background_color"] = $color;
        $child["default_style"]["background_opacity"] = $opacity;
        $child["mouseover_style"] = [
            "opacity"=> 1,
            "background_type"=> "color",
            "background_color"=> $color,
            "background_opacity"=> 0.8,
            "background_image_url"=> "",
            "background_image_opacity"=> 1,
            "background_image_scale"=> 1,
            "background_image_offset_x"=> 0,
            "background_image_offset_y"=> 0,
            "border_radius"=> 4,
            "border_width"=> 0,
            "border_style"=> "solid",
            "border_color"=> "#ffffff",
            "border_opacity"=> 1,
            "stroke_color"=> "#ffffff",
            "stroke_opacity"=> 0.75,
            "stroke_width"=> 0,
            "stroke_dasharray"=> "0",
            "stroke_linecap"=> "round",
            "icon_fill"=> "#000000",
            "parent_filters"=> [],
            "filters"=> []
        ];
        if($opacity == 0 && $color != "#ffffff"){
            $child["default_style"]["border_color"] = $color;
            $child["default_style"]["stroke_color"] = $color;
        }
    }

    function re_color($map,$data):array{
        $artboards = &$map["artboards"];
        foreach($artboards as &$artboard){
            foreach($artboard["children"] as &$child){
                if($child["type"] == "group"){
                    foreach($child["children"] as &$grandchildren){
                        $this->style_unit($grandchildren,$data);
                    }
                }
                else{
                    $this->style_unit($child,$data);
                }
            }
        }
        return $map;
    }

    function get_id(){
        return class_basename($this);
    }

    function get_info(){
        return [
            'id' => $this->get_id(),
            'colors' => $this->colors,
            'opacities' => $this->opacities
        ];
    }
    abstract function get_unit(Request $data);

    public static function instance(): ImageMapProBase
    {
        return new (config('overrides.image_map_pro'));
    }

    public function render(){}
}