<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UpdateAllGenreTraitsSeeder extends Seeder
{
    public function run()
    {
        $this->command->info("UPDATE BOBOT TRAIT GENRE");

        DB::table('genres')->update([
            'openness' => 50,
            'conscientiousness' => 50,
            'extroversion' => 50,
            'agreeableness' => 50,
            'neuroticism' => 50,
        ]);
        $this->command->info("✓ Reset semua genre ke default 50\n");

        $updates = [
            // ========== FANTASY & IMAGINATIVE (Openness tinggi) ==========
            ['ids' => [4, 11, 33, 34, 66, 67, 68, 71, 78, 165, 228, 229, 241], 
             'traits' => ['openness' => 88, 'conscientiousness' => 50, 'extroversion' => 55, 'agreeableness' => 60, 'neuroticism' => 45]],
            
            // ========== SCIENCE FICTION (Openness sangat tinggi) ==========
            ['ids' => [5, 16, 70, 82, 84, 187, 203, 271], 
             'traits' => ['openness' => 90, 'conscientiousness' => 55, 'extroversion' => 50, 'agreeableness' => 50, 'neuroticism' => 45]],
            
            // ========== ROMANCE (Extroversion & Agreeableness tinggi) ==========
            ['ids' => [6, 19, 36, 147, 148, 163, 177, 201, 229, 234], 
             'traits' => ['openness' => 65, 'conscientiousness' => 50, 'extroversion' => 85, 'agreeableness' => 78, 'neuroticism' => 60]],
            
            // ========== HORROR & THRILLER (Neuroticism tinggi) ==========
            ['ids' => [55, 63, 73, 83, 110, 111, 123, 207, 268, 272, 282], 
             'traits' => ['openness' => 60, 'conscientiousness' => 55, 'extroversion' => 45, 'agreeableness' => 45, 'neuroticism' => 85]],
            
            // ========== SELF HELP & DEVELOPMENT (Conscientiousness tinggi) ==========
            ['ids' => [51, 52, 189, 190, 191, 192, 193, 197, 198, 199, 200, 261], 
             'traits' => ['openness' => 60, 'conscientiousness' => 88, 'extroversion' => 55, 'agreeableness' => 60, 'neuroticism' => 65]],
            
            // ========== MYSTERY & DETECTIVE (Neuroticism sedang) ==========
            ['ids' => [73, 110, 111, 136, 156, 202, 207, 268, 272], 
             'traits' => ['openness' => 65, 'conscientiousness' => 65, 'extroversion' => 50, 'agreeableness' => 50, 'neuroticism' => 75]],
            
            // ========== LITERARY & CLASSICS (Openness tinggi) ==========
            ['ids' => [15, 28, 31, 38, 39, 44, 47, 69, 91, 105, 174], 
             'traits' => ['openness' => 82, 'conscientiousness' => 60, 'extroversion' => 50, 'agreeableness' => 65, 'neuroticism' => 55]],
            
            // ========== POETRY (Openness & Neuroticism) ==========
            ['ids' => [48], 
             'traits' => ['openness' => 85, 'conscientiousness' => 55, 'extroversion' => 45, 'agreeableness' => 65, 'neuroticism' => 70]],
            
            // ========== HISTORY & WAR (Conscientiousness tinggi) ==========
            ['ids' => [22, 23, 24, 25, 26, 35, 85, 99, 128, 141, 143, 159, 171], 
             'traits' => ['openness' => 70, 'conscientiousness' => 75, 'extroversion' => 45, 'agreeableness' => 55, 'neuroticism' => 55]],
            
            // ========== HISTORICAL FICTION ==========
            ['ids' => [22, 36], 
             'traits' => ['openness' => 75, 'conscientiousness' => 70, 'extroversion' => 50, 'agreeableness' => 60, 'neuroticism' => 55]],
            
            // ========== BIOGRAPHY & MEMOIR ==========
            ['ids' => [94, 95, 97, 98], 
             'traits' => ['openness' => 75, 'conscientiousness' => 70, 'extroversion' => 45, 'agreeableness' => 55, 'neuroticism' => 50]],
            
            // ========== NONFICTION ==========
            ['ids' => [93, 150, 151, 152, 179, 204, 226, 236, 243, 244, 252, 253, 277, 279], 
             'traits' => ['openness' => 75, 'conscientiousness' => 72, 'extroversion' => 45, 'agreeableness' => 55, 'neuroticism' => 50]],
            
            // ========== COMEDY & HUMOR (Extroversion tinggi) ==========
            ['ids' => [64, 65], 
             'traits' => ['openness' => 70, 'conscientiousness' => 45, 'extroversion' => 82, 'agreeableness' => 68, 'neuroticism' => 40]],
            
            // ========== CHILDREN & YOUNG ADULT ==========
            ['ids' => [1, 8, 12, 14, 30, 32, 45, 60, 61, 74, 75, 113, 149, 164], 
             'traits' => ['openness' => 75, 'conscientiousness' => 55, 'extroversion' => 65, 'agreeableness' => 72, 'neuroticism' => 45]],
            
            // ========== ADVENTURE & ACTION (Extroversion tinggi) ==========
            ['ids' => [7, 10, 133], 
             'traits' => ['openness' => 70, 'conscientiousness' => 55, 'extroversion' => 78, 'agreeableness' => 55, 'neuroticism' => 50]],
            
            // ========== PARANORMAL & SUPERNATURAL ==========
            ['ids' => [17, 18, 19, 20, 21, 56, 217], 
             'traits' => ['openness' => 80, 'conscientiousness' => 50, 'extroversion' => 55, 'agreeableness' => 55, 'neuroticism' => 65]],
            
            // ========== LGBTQ+ ==========
            ['ids' => [88, 112, 176, 278], 
             'traits' => ['openness' => 85, 'conscientiousness' => 55, 'extroversion' => 60, 'agreeableness' => 75, 'neuroticism' => 55]],
            
            // ========== REGIONAL LITERATURE ==========
            ['ids' => [40, 42, 53, 54, 79, 86, 89, 96, 100, 102, 103, 121, 122, 125, 127, 130, 131, 132, 135, 173, 181, 186, 205, 208, 209, 211, 224, 231, 232, 235, 237, 238, 239, 240, 246, 248, 256, 258, 263, 266, 281, 283], 
             'traits' => ['openness' => 78, 'conscientiousness' => 60, 'extroversion' => 50, 'agreeableness' => 65, 'neuroticism' => 50]],
            
            // ========== SOCIAL ISSUES ==========
            ['ids' => [29, 87, 114, 115, 134, 142, 158, 169, 170, 188, 210, 233, 243, 277, 285, 286], 
             'traits' => ['openness' => 75, 'conscientiousness' => 65, 'extroversion' => 50, 'agreeableness' => 65, 'neuroticism' => 60]],
            
            // ========== GRAPHIC NOVELS & COMICS ==========
            ['ids' => [183, 184, 185, 212, 213, 214, 215, 222, 225, 249, 250, 251, 288], 
             'traits' => ['openness' => 80, 'conscientiousness' => 50, 'extroversion' => 55, 'agreeableness' => 55, 'neuroticism' => 50]],
            
            // ========== EROTICA ==========
            ['ids' => [41, 145, 146], 
             'traits' => ['openness' => 75, 'conscientiousness' => 45, 'extroversion' => 70, 'agreeableness' => 60, 'neuroticism' => 55]],
            
            // ========== SHORT STORIES & ESSAYS ==========
            ['ids' => [107, 270, 276], 
             'traits' => ['openness' => 80, 'conscientiousness' => 55, 'extroversion' => 45, 'agreeableness' => 60, 'neuroticism' => 55]],
            
            // ========== PHILOSOPHY & SPIRITUALITY ==========
            ['ids' => [49, 50, 92, 161, 162, 194, 195, 227, 259, 260, 265], 
             'traits' => ['openness' => 85, 'conscientiousness' => 65, 'extroversion' => 40, 'agreeableness' => 60, 'neuroticism' => 55]],
            
            // ========== WESTERNS & MILITARY ==========
            ['ids' => [126, 247], 
             'traits' => ['openness' => 60, 'conscientiousness' => 65, 'extroversion' => 60, 'agreeableness' => 50, 'neuroticism' => 60]],
            
            // ========== APOCALYPTIC & DYSTOPIA ==========
            ['ids' => [3, 9, 84], 
             'traits' => ['openness' => 75, 'conscientiousness' => 55, 'extroversion' => 45, 'agreeableness' => 50, 'neuroticism' => 80]],
            
            // ========== YOUNG ADULT FICTION ==========
            ['ids' => [1, 2, 8, 43, 113, 149, 164, 201], 
             'traits' => ['openness' => 75, 'conscientiousness' => 55, 'extroversion' => 65, 'agreeableness' => 70, 'neuroticism' => 55]],
            
            // ========== DYSTOPIA ==========
            ['ids' => [3], 
             'traits' => ['openness' => 80, 'conscientiousness' => 55, 'extroversion' => 45, 'agreeableness' => 50, 'neuroticism' => 75]],
            
            // ========== FICTION UMUM ==========
            ['ids' => [2, 43, 116], 
             'traits' => ['openness' => 70, 'conscientiousness' => 55, 'extroversion' => 55, 'agreeableness' => 65, 'neuroticism' => 55]],
        ];

        // Eksekusi update
        $totalUpdated = 0;
        foreach ($updates as $update) {
            $count = DB::table('genres')
                ->whereIn('id', $update['ids'])
                ->update($update['traits']);
            $totalUpdated += $count;
            $this->command->info("  ✓ Updated {$count} genre(s)");
        }

        $this->command->info("\n✓ Total genre updated: {$totalUpdated}\n");

        // Tampilkan sample hasil
        $this->command->info("Sample genre yang sudah diupdate:");
        $samples = DB::table('genres')
            ->where('openness', '!=', 50)
            ->limit(20)
            ->get(['id', 'name', 'openness', 'conscientiousness', 'extroversion', 'agreeableness', 'neuroticism']);
        
        foreach ($samples as $sample) {
            $this->command->line("  ID {$sample->id}: {$sample->name} - O:{$sample->openness} C:{$sample->conscientiousness} E:{$sample->extroversion} A:{$sample->agreeableness} N:{$sample->neuroticism}");
        }

        $this->command->info("\nSelesai! Semua genre sudah memiliki bobot trait.");
    }
}