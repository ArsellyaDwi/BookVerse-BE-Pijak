<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UpdateAllGenreTraitsSeeder extends Seeder
{
    public function run()
    {
        $this->command->info("UPDATE BOBOT TRAIT GENRE - 288 GENRE");

        DB::table('genres')->update([
            'openness' => 50,
            'conscientiousness' => 50,
            'extroversion' => 50,
            'agreeableness' => 50,
            'neuroticism' => 50,
        ]);
        $this->command->info("✓ Reset semua genre ke default 50\n");

        $updates = [
            // ========== ID 1-20 ==========
            ['ids' => [1], 'traits' => ['openness' => 75, 'conscientiousness' => 55, 'extroversion' => 65, 'agreeableness' => 72, 'neuroticism' => 45]], // Young Adult
            ['ids' => [2], 'traits' => ['openness' => 70, 'conscientiousness' => 55, 'extroversion' => 55, 'agreeableness' => 65, 'neuroticism' => 55]], // Fiction
            ['ids' => [3], 'traits' => ['openness' => 73, 'conscientiousness' => 54, 'extroversion' => 43, 'agreeableness' => 49, 'neuroticism' => 82]], // Dystopia
            ['ids' => [4], 'traits' => ['openness' => 88, 'conscientiousness' => 52, 'extroversion' => 54, 'agreeableness' => 61, 'neuroticism' => 44]], // Fantasy
            ['ids' => [5], 'traits' => ['openness' => 92, 'conscientiousness' => 56, 'extroversion' => 51, 'agreeableness' => 52, 'neuroticism' => 43]], // Science Fiction
            ['ids' => [6], 'traits' => ['openness' => 64, 'conscientiousness' => 48, 'extroversion' => 86, 'agreeableness' => 79, 'neuroticism' => 58]], // Romance
            ['ids' => [7], 'traits' => ['openness' => 72, 'conscientiousness' => 53, 'extroversion' => 79, 'agreeableness' => 56, 'neuroticism' => 48]], // Adventure
            ['ids' => [8], 'traits' => ['openness' => 74, 'conscientiousness' => 54, 'extroversion' => 66, 'agreeableness' => 70, 'neuroticism' => 52]], // Teen
            ['ids' => [9], 'traits' => ['openness' => 70, 'conscientiousness' => 55, 'extroversion' => 42, 'agreeableness' => 48, 'neuroticism' => 85]], // Post Apocalyptic
            ['ids' => [10], 'traits' => ['openness' => 61, 'conscientiousness' => 68, 'extroversion' => 80, 'agreeableness' => 47, 'neuroticism' => 46]], // Action
            ['ids' => [11], 'traits' => ['openness' => 90, 'conscientiousness' => 50, 'extroversion' => 53, 'agreeableness' => 59, 'neuroticism' => 42]], // Magic
            ['ids' => [12], 'traits' => ['openness' => 72, 'conscientiousness' => 52, 'extroversion' => 62, 'agreeableness' => 75, 'neuroticism' => 43]], // Childrens
            ['ids' => [13], 'traits' => ['openness' => 65, 'conscientiousness' => 55, 'extroversion' => 55, 'agreeableness' => 60, 'neuroticism' => 50]], // Audiobook
            ['ids' => [14], 'traits' => ['openness' => 73, 'conscientiousness' => 54, 'extroversion' => 63, 'agreeableness' => 73, 'neuroticism' => 44]], // Middle Grade
            ['ids' => [15], 'traits' => ['openness' => 83, 'conscientiousness' => 62, 'extroversion' => 48, 'agreeableness' => 64, 'neuroticism' => 54]], // Classics
            ['ids' => [16], 'traits' => ['openness' => 89, 'conscientiousness' => 54, 'extroversion' => 52, 'agreeableness' => 54, 'neuroticism' => 44]], // Science Fiction Fantasy
            ['ids' => [17], 'traits' => ['openness' => 75, 'conscientiousness' => 50, 'extroversion' => 52, 'agreeableness' => 52, 'neuroticism' => 68]], // Vampires
            ['ids' => [18], 'traits' => ['openness' => 78, 'conscientiousness' => 49, 'extroversion' => 53, 'agreeableness' => 53, 'neuroticism' => 67]], // Paranormal
            ['ids' => [19], 'traits' => ['openness' => 77, 'conscientiousness' => 48, 'extroversion' => 70, 'agreeableness' => 72, 'neuroticism' => 62]], // Paranormal Romance
            ['ids' => [20], 'traits' => ['openness' => 76, 'conscientiousness' => 48, 'extroversion' => 54, 'agreeableness' => 54, 'neuroticism' => 65]], // Supernatural

            // ========== ID 21-40 ==========
            ['ids' => [21], 'traits' => ['openness' => 80, 'conscientiousness' => 50, 'extroversion' => 55, 'agreeableness' => 55, 'neuroticism' => 60]], // Urban Fantasy
            ['ids' => [22], 'traits' => ['openness' => 74, 'conscientiousness' => 71, 'extroversion' => 48, 'agreeableness' => 58, 'neuroticism' => 52]], // Historical Fiction
            ['ids' => [23], 'traits' => ['openness' => 68, 'conscientiousness' => 72, 'extroversion' => 46, 'agreeableness' => 55, 'neuroticism' => 50]], // Historical
            ['ids' => [24], 'traits' => ['openness' => 62, 'conscientiousness' => 73, 'extroversion' => 47, 'agreeableness' => 52, 'neuroticism' => 56]], // War
            ['ids' => [25], 'traits' => ['openness' => 60, 'conscientiousness' => 70, 'extroversion' => 45, 'agreeableness' => 54, 'neuroticism' => 60]], // Holocaust
            ['ids' => [26], 'traits' => ['openness' => 61, 'conscientiousness' => 71, 'extroversion' => 46, 'agreeableness' => 53, 'neuroticism' => 58]], // World War II
            ['ids' => [27], 'traits' => ['openness' => 72, 'conscientiousness' => 58, 'extroversion' => 50, 'agreeableness' => 62, 'neuroticism' => 52]], // Books About Books
            ['ids' => [28], 'traits' => ['openness' => 81, 'conscientiousness' => 58, 'extroversion' => 48, 'agreeableness' => 62, 'neuroticism' => 52]], // Literature
            ['ids' => [29], 'traits' => ['openness' => 68, 'conscientiousness' => 65, 'extroversion' => 55, 'agreeableness' => 55, 'neuroticism' => 55]], // Politics
            ['ids' => [30], 'traits' => ['openness' => 65, 'conscientiousness' => 60, 'extroversion' => 60, 'agreeableness' => 65, 'neuroticism' => 50]], // School
            ['ids' => [31], 'traits' => ['openness' => 75, 'conscientiousness' => 58, 'extroversion' => 52, 'agreeableness' => 60, 'neuroticism' => 52]], // Novels
            ['ids' => [32], 'traits' => ['openness' => 64, 'conscientiousness' => 62, 'extroversion' => 58, 'agreeableness' => 62, 'neuroticism' => 54]], // Read For School
            ['ids' => [33], 'traits' => ['openness' => 92, 'conscientiousness' => 52, 'extroversion' => 53, 'agreeableness' => 58, 'neuroticism' => 42]], // Epic Fantasy
            ['ids' => [34], 'traits' => ['openness' => 89, 'conscientiousness' => 51, 'extroversion' => 52, 'agreeableness' => 59, 'neuroticism' => 43]], // High Fantasy
            ['ids' => [35], 'traits' => ['openness' => 60, 'conscientiousness' => 74, 'extroversion' => 46, 'agreeableness' => 52, 'neuroticism' => 57]], // Civil War
            ['ids' => [36], 'traits' => ['openness' => 66, 'conscientiousness' => 52, 'extroversion' => 73, 'agreeableness' => 74, 'neuroticism' => 56]], // Historical Romance
            ['ids' => [37], 'traits' => ['openness' => 75, 'conscientiousness' => 55, 'extroversion' => 46, 'agreeableness' => 52, 'neuroticism' => 70]], // Gothic
            ['ids' => [38], 'traits' => ['openness' => 70, 'conscientiousness' => 65, 'extroversion' => 47, 'agreeableness' => 56, 'neuroticism' => 52]], // 19th Century
            ['ids' => [39], 'traits' => ['openness' => 84, 'conscientiousness' => 61, 'extroversion' => 47, 'agreeableness' => 63, 'neuroticism' => 53]], // Classic Literature
            ['ids' => [40], 'traits' => ['openness' => 78, 'conscientiousness' => 58, 'extroversion' => 50, 'agreeableness' => 62, 'neuroticism' => 48]], // Japan

            // ========== ID 41-60 ==========
            ['ids' => [41], 'traits' => ['openness' => 68, 'conscientiousness' => 58, 'extroversion' => 58, 'agreeableness' => 58, 'neuroticism' => 52]], // Adult
            ['ids' => [42], 'traits' => ['openness' => 76, 'conscientiousness' => 58, 'extroversion' => 50, 'agreeableness' => 62, 'neuroticism' => 48]], // Asia
            ['ids' => [43], 'traits' => ['openness' => 70, 'conscientiousness' => 55, 'extroversion' => 55, 'agreeableness' => 62, 'neuroticism' => 52]], // Adult Fiction
            ['ids' => [44], 'traits' => ['openness' => 80, 'conscientiousness' => 60, 'extroversion' => 48, 'agreeableness' => 62, 'neuroticism' => 50]], // British Literature
            ['ids' => [45], 'traits' => ['openness' => 66, 'conscientiousness' => 56, 'extroversion' => 64, 'agreeableness' => 68, 'neuroticism' => 50]], // High School
            ['ids' => [46], 'traits' => ['openness' => 78, 'conscientiousness' => 55, 'extroversion' => 52, 'agreeableness' => 60, 'neuroticism' => 55]], // Plays
            ['ids' => [47], 'traits' => ['openness' => 74, 'conscientiousness' => 56, 'extroversion' => 58, 'agreeableness' => 62, 'neuroticism' => 58]], // Drama
            ['ids' => [48], 'traits' => ['openness' => 86, 'conscientiousness' => 53, 'extroversion' => 44, 'agreeableness' => 63, 'neuroticism' => 71]], // Poetry
            ['ids' => [49], 'traits' => ['openness' => 88, 'conscientiousness' => 58, 'extroversion' => 42, 'agreeableness' => 58, 'neuroticism' => 55]], // Philosophy
            ['ids' => [50], 'traits' => ['openness' => 82, 'conscientiousness' => 55, 'extroversion' => 48, 'agreeableness' => 60, 'neuroticism' => 52]], // Spirituality
            ['ids' => [51], 'traits' => ['openness' => 58, 'conscientiousness' => 89, 'extroversion' => 53, 'agreeableness' => 61, 'neuroticism' => 63]], // Self Help
            ['ids' => [52], 'traits' => ['openness' => 62, 'conscientiousness' => 85, 'extroversion' => 55, 'agreeableness' => 65, 'neuroticism' => 55]], // Inspirational
            ['ids' => [53], 'traits' => ['openness' => 72, 'conscientiousness' => 62, 'extroversion' => 46, 'agreeableness' => 58, 'neuroticism' => 52]], // Russia
            ['ids' => [54], 'traits' => ['openness' => 75, 'conscientiousness' => 60, 'extroversion' => 45, 'agreeableness' => 58, 'neuroticism' => 54]], // Russian Literature
            ['ids' => [55], 'traits' => ['openness' => 62, 'conscientiousness' => 58, 'extroversion' => 48, 'agreeableness' => 48, 'neuroticism' => 76]], // Crime
            ['ids' => [56], 'traits' => ['openness' => 70, 'conscientiousness' => 52, 'extroversion' => 50, 'agreeableness' => 58, 'neuroticism' => 60]], // Angels
            ['ids' => [57], 'traits' => ['openness' => 68, 'conscientiousness' => 60, 'extroversion' => 50, 'agreeableness' => 60, 'neuroticism' => 48]], // Canada
            ['ids' => [58], 'traits' => ['openness' => 72, 'conscientiousness' => 55, 'extroversion' => 58, 'agreeableness' => 68, 'neuroticism' => 50]], // Coming Of Age
            ['ids' => [59], 'traits' => ['openness' => 65, 'conscientiousness' => 55, 'extroversion' => 52, 'agreeableness' => 65, 'neuroticism' => 48]], // Animals
            ['ids' => [60], 'traits' => ['openness' => 70, 'conscientiousness' => 55, 'extroversion' => 60, 'agreeableness' => 72, 'neuroticism' => 45]], // Juvenile

            // ========== ID 61-80 ==========
            ['ids' => [61], 'traits' => ['openness' => 68, 'conscientiousness' => 56, 'extroversion' => 58, 'agreeableness' => 70, 'neuroticism' => 46]], // Chapter Books
            ['ids' => [62], 'traits' => ['openness' => 70, 'conscientiousness' => 62, 'extroversion' => 52, 'agreeableness' => 58, 'neuroticism' => 48]], // American
            ['ids' => [63], 'traits' => ['openness' => 58, 'conscientiousness' => 52, 'extroversion' => 44, 'agreeableness' => 44, 'neuroticism' => 88]], // Horror
            ['ids' => [64], 'traits' => ['openness' => 68, 'conscientiousness' => 43, 'extroversion' => 84, 'agreeableness' => 69, 'neuroticism' => 39]], // Humor
            ['ids' => [65], 'traits' => ['openness' => 70, 'conscientiousness' => 42, 'extroversion' => 86, 'agreeableness' => 70, 'neuroticism' => 38]], // Comedy
            ['ids' => [66], 'traits' => ['openness' => 82, 'conscientiousness' => 52, 'extroversion' => 56, 'agreeableness' => 65, 'neuroticism' => 45]], // Fairy Tales
            ['ids' => [67], 'traits' => ['openness' => 84, 'conscientiousness' => 54, 'extroversion' => 52, 'agreeableness' => 60, 'neuroticism' => 48]], // Mythology
            ['ids' => [68], 'traits' => ['openness' => 85, 'conscientiousness' => 53, 'extroversion' => 51, 'agreeableness' => 59, 'neuroticism' => 47]], // Greek Mythology
            ['ids' => [69], 'traits' => ['openness' => 76, 'conscientiousness' => 56, 'extroversion' => 58, 'agreeableness' => 62, 'neuroticism' => 50]], // Contemporary
            ['ids' => [70], 'traits' => ['openness' => 78, 'conscientiousness' => 54, 'extroversion' => 55, 'agreeableness' => 55, 'neuroticism' => 52]], // Time Travel
            ['ids' => [71], 'traits' => ['openness' => 86, 'conscientiousness' => 50, 'extroversion' => 52, 'agreeableness' => 58, 'neuroticism' => 44]], // Dragons
            ['ids' => [72], 'traits' => ['openness' => 84, 'conscientiousness' => 56, 'extroversion' => 54, 'agreeableness' => 58, 'neuroticism' => 46]], // Epic
            ['ids' => [73], 'traits' => ['openness' => 66, 'conscientiousness' => 67, 'extroversion' => 49, 'agreeableness' => 51, 'neuroticism' => 74]], // Mystery
            ['ids' => [74], 'traits' => ['openness' => 70, 'conscientiousness' => 54, 'extroversion' => 60, 'agreeableness' => 72, 'neuroticism' => 44]], // Picture Books
            ['ids' => [75], 'traits' => ['openness' => 72, 'conscientiousness' => 53, 'extroversion' => 62, 'agreeableness' => 74, 'neuroticism' => 43]], // Kids
            ['ids' => [76], 'traits' => ['openness' => 60, 'conscientiousness' => 52, 'extroversion' => 48, 'agreeableness' => 48, 'neuroticism' => 78]], // Monsters
            ['ids' => [77], 'traits' => ['openness' => 62, 'conscientiousness' => 58, 'extroversion' => 55, 'agreeableness' => 60, 'neuroticism' => 50]], // Food
            ['ids' => [78], 'traits' => ['openness' => 88, 'conscientiousness' => 52, 'extroversion' => 53, 'agreeableness' => 62, 'neuroticism' => 48]], // Magical Realism
            ['ids' => [79], 'traits' => ['openness' => 74, 'conscientiousness' => 58, 'extroversion' => 50, 'agreeableness' => 60, 'neuroticism' => 50]], // India
            ['ids' => [80], 'traits' => ['openness' => 68, 'conscientiousness' => 55, 'extroversion' => 60, 'agreeableness' => 65, 'neuroticism' => 48]], // Book Club

            // ========== ID 81-100 ==========
            ['ids' => [81], 'traits' => ['openness' => 65, 'conscientiousness' => 50, 'extroversion' => 50, 'agreeableness' => 55, 'neuroticism' => 55]], // Unfinished
            ['ids' => [82], 'traits' => ['openness' => 85, 'conscientiousness' => 55, 'extroversion' => 52, 'agreeableness' => 52, 'neuroticism' => 45]], // Space Opera
            ['ids' => [83], 'traits' => ['openness' => 62, 'conscientiousness' => 66, 'extroversion' => 47, 'agreeableness' => 48, 'neuroticism' => 84]], // Thriller
            ['ids' => [84], 'traits' => ['openness' => 70, 'conscientiousness' => 55, 'extroversion' => 44, 'agreeableness' => 48, 'neuroticism' => 80]], // Apocalyptic
            ['ids' => [85], 'traits' => ['openness' => 68, 'conscientiousness' => 68, 'extroversion' => 46, 'agreeableness' => 56, 'neuroticism' => 54]], // Victorian
            ['ids' => [86], 'traits' => ['openness' => 70, 'conscientiousness' => 60, 'extroversion' => 50, 'agreeableness' => 60, 'neuroticism' => 48]], // Scotland
            ['ids' => [87], 'traits' => ['openness' => 82, 'conscientiousness' => 62, 'extroversion' => 58, 'agreeableness' => 70, 'neuroticism' => 52]], // Feminism
            ['ids' => [88], 'traits' => ['openness' => 87, 'conscientiousness' => 53, 'extroversion' => 58, 'agreeableness' => 76, 'neuroticism' => 54]], // LGBT
            ['ids' => [89], 'traits' => ['openness' => 78, 'conscientiousness' => 62, 'extroversion' => 55, 'agreeableness' => 68, 'neuroticism' => 52]], // African American
            ['ids' => [90], 'traits' => ['openness' => 72, 'conscientiousness' => 58, 'extroversion' => 60, 'agreeableness' => 58, 'neuroticism' => 48]], // New York
            ['ids' => [91], 'traits' => ['openness' => 82, 'conscientiousness' => 60, 'extroversion' => 50, 'agreeableness' => 62, 'neuroticism' => 52]], // Modern Classics
            ['ids' => [92], 'traits' => ['openness' => 75, 'conscientiousness' => 58, 'extroversion' => 48, 'agreeableness' => 60, 'neuroticism' => 50]], // Religion
            ['ids' => [93], 'traits' => ['openness' => 72, 'conscientiousness' => 70, 'extroversion' => 46, 'agreeableness' => 56, 'neuroticism' => 48]], // Nonfiction
            ['ids' => [94], 'traits' => ['openness' => 73, 'conscientiousness' => 72, 'extroversion' => 44, 'agreeableness' => 53, 'neuroticism' => 49]], // Memoir
            ['ids' => [95], 'traits' => ['openness' => 72, 'conscientiousness' => 73, 'extroversion' => 45, 'agreeableness' => 54, 'neuroticism' => 48]], // Biography
            ['ids' => [96], 'traits' => ['openness' => 70, 'conscientiousness' => 60, 'extroversion' => 50, 'agreeableness' => 62, 'neuroticism' => 48]], // Ireland
            ['ids' => [97], 'traits' => ['openness' => 71, 'conscientiousness' => 71, 'extroversion' => 44, 'agreeableness' => 54, 'neuroticism' => 48]], // Autobiography
            ['ids' => [98], 'traits' => ['openness' => 72, 'conscientiousness' => 72, 'extroversion' => 44, 'agreeableness' => 54, 'neuroticism' => 48]], // Biography Memoir
            ['ids' => [99], 'traits' => ['openness' => 69, 'conscientiousness' => 76, 'extroversion' => 44, 'agreeableness' => 54, 'neuroticism' => 53]], // History
            ['ids' => [100], 'traits' => ['openness' => 74, 'conscientiousness' => 62, 'extroversion' => 48, 'agreeableness' => 60, 'neuroticism' => 50]], // Irish Literature
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