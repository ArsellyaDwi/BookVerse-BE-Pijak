<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

// ========== SET PROFIL USER DI SINI ==========
// Ubah nilai sesuai keinginan untuk test genre apa yang keluar

// Profil 1: NETRAL (semua 50)
// $user = [
//     'extroversion' => 50,
//     'neuroticism' => 50,
//     'agreeableness' => 50,
//     'conscientiousness' => 50,
//     'openness' => 50,
// ];

// Profil 2: EKSTROVERT (suka bersosialisasi)
$user = [
    'extroversion' => 90,
    'neuroticism' => 40,
    'agreeableness' => 80,
    'conscientiousness' => 50,
    'openness' => 60,
];

// Profil 3: NEUROTIK (cemas, mudah stres)
// $user = [
//     'extroversion' => 45,
//     'neuroticism' => 90,
//     'agreeableness' => 50,
//     'conscientiousness' => 55,
//     'openness' => 60,
// ];

// // Profil 4: OPENNESS TINGGI (suka hal baru, imajinatif)
// $user = [
//     'extroversion' => 50,
//     'neuroticism' => 45,
//     'agreeableness' => 55,
//     'conscientiousness' => 50,
//     'openness' => 90,
// ];

// Profil 5: CONSCIENTIOUSNESS TINGGI (teratur, disiplin)
// $user = [
//     'extroversion' => 50,
//     'neuroticism' => 50,
//     'agreeableness' => 55,
//     'conscientiousness' => 90,
//     'openness' => 60,
// ];

// ========== AMBIL SEMUA GENRE YANG PUNYA BUKU ==========
$genres = DB::table('genres')
    ->join('book_genre', 'genres.id', '=', 'book_genre.genre_id')
    ->select(
        'genres.id',
        'genres.name',
        'genres.openness',
        'genres.conscientiousness',
        'genres.extroversion',
        'genres.agreeableness',
        'genres.neuroticism',
        DB::raw('COUNT(book_genre.book_id) as total_books')
    )
    ->groupBy('genres.id', 'genres.name', 
        'genres.openness', 'genres.conscientiousness', 
        'genres.extroversion', 'genres.agreeableness', 'genres.neuroticism')
    ->having('total_books', '>', 0)
    ->get();

$maxDistance = sqrt(5 * 100 * 100);
$results = [];

foreach ($genres as $genre) {
    $totalDiff = pow($user['extroversion'] - $genre->extroversion, 2)
               + pow($user['neuroticism'] - $genre->neuroticism, 2)
               + pow($user['agreeableness'] - $genre->agreeableness, 2)
               + pow($user['conscientiousness'] - $genre->conscientiousness, 2)
               + pow($user['openness'] - $genre->openness, 2);
    
    $similarity = (1 - (sqrt($totalDiff) / $maxDistance)) * 100;
    $similarity = round($similarity, 2);
    
    $results[] = [
        'id' => $genre->id,
        'name' => $genre->name,
        'score' => $similarity,
        'books' => $genre->total_books
    ];
}

// Urutkan dari skor tertinggi
usort($results, fn($a, $b) => $b['score'] <=> $a['score']);

// ========== TAMPILKAN HASIL ==========
echo "\n";
echo "========================================\n";
echo "PROFIL USER:\n";
echo "Extroversion: {$user['extroversion']}\n";
echo "Neuroticism: {$user['neuroticism']}\n";
echo "Agreeableness: {$user['agreeableness']}\n";
echo "Conscientiousness: {$user['conscientiousness']}\n";
echo "Openness: {$user['openness']}\n";
echo "========================================\n\n";

echo "SEMUA GENRE REKOMENDASI (urut dari tertinggi):\n";
echo "========================================\n";
echo "No | Genre | Score | Books\n";
echo "---|-------|-------|------\n";

for ($i = 0; $i < count($results); $i++) {
    $no = $i + 1;
    echo str_pad($no, 3) . "| " 
         . str_pad($results[$i]['name'], 25) . "| " 
         . str_pad($results[$i]['score'] . '%', 7) . "| " 
         . $results[$i]['books'] . " books\n";
}

echo "\n5 GENRE TERATAS:\n";
echo "========================================\n";
for ($i = 0; $i < 5; $i++) {
    echo ($i+1) . ". " . $results[$i]['name'] . " - " . $results[$i]['score'] . "% (" . $results[$i]['books'] . " books)\n";
}