<?php

namespace Gwd\LaravelNovaConfigs\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Gwd\LaravelNovaConfigs\Helpers\Configs;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    
    public function setup(){
        return [
            'keys' => config('laravel-nova-config'),
            'data' => Configs::all()
        ];
    }
    public function index(){
        return Configs::all();
    }
    public function store(){
        return ['hallo world'];
    }
    public function save(Request $request){
        $data = $request->validate([
            'key' => 'string',
            'fields' => 'array'
        ]);
        
        $key = $data['key'];
        $keys = array_keys($data['fields']);
        if($keys && count($keys) > 0){
            foreach($data['fields'] as $name => $value){
                $field_name = $key . '.' . $name;
                
                $item = Configs::set($field_name, $value);
            }
        }

        return [
            'status' => 1,
            'data' => Configs::all()
        ];
    }
}
