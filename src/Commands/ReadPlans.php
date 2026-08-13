<?php

namespace Ro749\ListingUtils\Commands;

use Illuminate\Console\Command;
use Ro749\SharedUtils\Readers\Reader;
use Ro749\SharedUtils\Readers\DbRead;
use Ro749\SharedUtils\Readers\DbUpdate;
use Ro749\SharedUtils\Readers\MigrationHelper;

class ReadPlans extends Command
{
    protected $signature = 'read:plans {file}';

    protected $description = 'transform plans to database format';

    public function handle()
    {
        $file = $this->argument('file');
        $reader = new Reader();
        $data = $reader->read_csv($file);
        $plans = [];
        $plan_lines = [];
        foreach($data as $key => $d) {
            $new_plan = ['title' => $d['plan']];
            if(!empty($d['descuento'])){
                $new_plan['discount'] = $d['descuento'];
            }
            if(!empty($d['meses'])){
                $new_plan['final_date'] = $d['meses'];
            }
            $plans[] = $new_plan;
            
            if(!empty($d['enganche'])){
                $plan_lines[] = [
                    'plan_id' => $key+1,
                    'description' => 'Enganche',
                    'percent' => $d['enganche']
                ];
            }
            if(!empty($d['meses'])){
                $plan_lines[] = [
                    'plan_id' => $key+1,
                    'description' => 'Plazo',
                    'percent' => $d['plazo'],
                    'months' => 'true'
                ];
            }
            if(!empty($d['entrega'])){
                $plan_lines[] = [
                    'plan_id' => $key+1,
                    'description' => 'Entrega',
                    'percent' => $d['entrega']
                ];
            }
        }
        $migration_text = '';
        $migration_text .= MigrationHelper::generate_migration_for_fill_data('plans', $plans);
        $migration_text .= MigrationHelper::generate_migration_for_fill_data('plan_lines', $plan_lines);
        MigrationHelper::create_migration_file('create_plans_table', $migration_text);
    }
    
}