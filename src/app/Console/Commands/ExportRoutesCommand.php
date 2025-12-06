<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Route;

class ExportRoutesCommand extends Command
{
    protected $signature = 'routes:export';
    protected $description = 'Export route list to CSV file';

    public function handle()
    {
        $routes = collect(Route::getRoutes())->map(function ($route) {
            return [
                'method' => implode('|', $route->methods()),
                'uri' => $route->uri(),
                'action' => $route->getActionName(),
            ];
        });

        $filepath = storage_path('routes.csv');
        $file = fopen($filepath, 'w');

        fputcsv($file, ['METHOD', 'URI', 'ACTION']);

        foreach ($routes as $route) {
            fputcsv($file, $route);
        }

        fclose($file);

        $this->info("Exported to: {$filepath}");
    }
}
