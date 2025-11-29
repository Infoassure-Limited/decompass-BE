<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\PerfomanceAndMonitoring\InfoNeedCategory;
use App\Models\PerfomanceAndMonitoring\InfoNeed;

class InfoNeedsSeeder extends Seeder
{
    public function run()
    {
        $categories = [
            'Access Control' => [
                'User access reviews',
                'Privileged account usage',
                'Account provisioning metrics',
            ],
            'Incident Management' => [
                'Number of incidents',
                'Time to detect',
                'Time to contain',
                'Root cause recurrence',
            ],
            'Availability & Resilience' => [
                'System uptime',
                'MTTR (Mean time to repair)',
                'Backup success rate',
            ],
            'Compliance & Audit' => [
                'Audit findings',
                'Policy exceptions',
                'Regulatory status',
            ],
            'Risk & Vulnerability' => [
                'Open vulnerabilities',
                'Patch lag',
                'Risk treatment progress',
            ],
            'Encryption & Data Protection' => [
                'Encrypted storage coverage',
                'Key rotation status',
            ],
        ];

        foreach ($categories as $name => $needs) {
            $cat = InfoNeedCategory::create([
                'name' => $name,
                'slug' => Str::slug($name),
                'description' => "Pre-populated info needs for {$name}"
            ]);

            $batch = [];
            foreach ($needs as $needName) {
                $batch[] = [
                    'info_need_category_id' => $cat->id,
                    'name' => $needName,
                    'code' => strtoupper(Str::slug($cat->slug . '-' . $needName, '_')),
                    'description' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            \DB::table('info_needs')->insert($batch);
        }
    }
}
