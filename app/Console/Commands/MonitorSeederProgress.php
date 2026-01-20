<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SeederProgress;
use App\Models\ProcessingLog;
use Illuminate\Support\Facades\DB;

class MonitorSeederProgress extends Command
{
    protected $signature = 'seeder:monitor 
                            {--seeder=ProductOptionsAiSeeder : Seeder name to monitor}
                            {--interval=5 : Refresh interval in seconds}
                            {--limit=20 : Number of recent logs to show}';
    
    protected $description = 'Monitor seeder progress in real-time';

    public function handle()
    {
        $seederName = $this->option('seeder');
        $interval = $this->option('interval');
        $limit = $this->option('limit');
        
        $this->info("🔍 Monitoring seeder: {$seederName}");
        $this->info("⏱️ Refresh interval: {$interval} seconds");
        $this->line(str_repeat('-', 60));
        
        $previousLogCount = 0;
        
        while (true) {
            $progress = SeederProgress::where('seeder_name', $seederName)->first();
            
            if (!$progress) {
                $this->error("Seeder '{$seederName}' not found!");
                break;
            }
            
            // عرض حالة التقدم
            $this->displayProgress($progress);
            
            // عرض السجلات الجديدة
            $this->displayNewLogs($progress, $previousLogCount, $limit);
            
            $previousLogCount = ProcessingLog::where('seeder_progress_id', $progress->id)->count();
            
            // انتظار الفترة المحددة
            sleep($interval);
            
            // مسح الشاشة (للتحديث)
            $this->clearScreen();
        }
    }
    
    private function displayProgress($progress)
    {
        $this->info("\n📊 SEEDER PROGRESS - " . now()->format('Y-m-d H:i:s'));
        $this->line(str_repeat('-', 60));
        $this->info("Status: " . strtoupper($progress->status));
        $this->info("Total Processed: {$progress->total_processed}");
        $this->info("Success: {$progress->success_count} | Failed: {$progress->fail_count} | Skipped: {$progress->skipped_count}");
        
        if ($progress->started_at) {
            $duration = $progress->started_at->diff(now());
            $this->info("Running for: {$duration->h}h {$duration->i}m {$duration->s}s");
        }
        
        // إحصائيات الذاكرة
        $memoryStats = ProcessingLog::where('seeder_progress_id', $progress->id)
            ->select(DB::raw('AVG(memory_usage) as avg_memory, MAX(memory_usage) as max_memory'))
            ->first();
            
        if ($memoryStats) {
            $this->info("Avg Memory: " . $this->formatBytes($memoryStats->avg_memory));
            $this->info("Max Memory: " . $this->formatBytes($memoryStats->max_memory));
        }
        
        $this->line(str_repeat('-', 60));
    }
    
    private function displayNewLogs($progress, $previousCount, $limit)
    {
        $logs = ProcessingLog::where('seeder_progress_id', $progress->id)
            ->where('id', '>', $previousCount)
            ->latest()
            ->limit($limit)
            ->get();
            
        if ($logs->count() > 0) {
            $this->info("\n🆕 RECENT ACTIVITY:");
            foreach ($logs as $log) {
                $time = $log->created_at->format('H:i:s');
                $memory = $this->formatBytes($log->memory_usage);
                $this->line("[{$time}] {$log->step} - {$log->status} ({$memory})");
            }
        }
    }
    
    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);
        
        return round($bytes, $precision) . ' ' . $units[$pow];
    }
    
    private function clearScreen()
    {
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            system('cls');
        } else {
            system('clear');
        }
    }
}