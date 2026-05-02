<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class QuestionsSeeder extends Seeder
{
    public function run()
    {
        $client = new \MongoDB\Client(env('MONGODB_URI'));
        $db = $client->selectDatabase(env('MONGODB_DATABASE', 'DBnurtura'));
        $collection = $db->questions;

        $now = new \MongoDB\BSON\UTCDateTime();

        $questions = [
            [
                "field_key" => "perasaan_sedih_atau_mudah_menangis",
                "question_text" => "Apakah Anda merasa sedih atau mudah menangis?",
                "options" => ["Ya", "Tidak", "Kadang-kadang"],
                "value_map" => [
                    "Ya" => 2,
                    "Kadang-kadang" => 1,
                    "Tidak" => 0
                ],
                "category" => "emosional",
                "ml_index" => 0,
                "order" => 1,
                "version" => 1,
                "created_at" => $now,
                "updated_at" => null
            ],
            [
                "field_key" => "mudah_marah_terhadap_bayi_dan_pasangan",
                "question_text" => "Apakah Anda mudah marah terhadap bayi atau pasangan?",
                "options" => ["Ya", "Tidak", "Kadang-kadang"],
                "value_map" => [
                    "Ya" => 2,
                    "Kadang-kadang" => 1,
                    "Tidak" => 0
                ],
                "category" => "emosional",
                "ml_index" => 1,
                "order" => 2,
                "version" => 1,
                "created_at" => $now,
                "updated_at" => null
            ],
            [
                "field_key" => "kesulitan_tidur_di_malam_hari",
                "question_text" => "Apakah Anda mengalami kesulitan tidur di malam hari?",
                "options" => ["Ya", "Tidak", "Sering"],
                "value_map" => [
                    "Ya" => 2,
                    "Sering" => 2,
                    "Tidak" => 0
                ],
                "category" => "tidur",
                "ml_index" => 2,
                "order" => 3,
                "version" => 1,
                "created_at" => $now,
                "updated_at" => null
            ],
            [
                "field_key" => "kesulitan_konsentrasi_atau_mengambil_keputusan",
                "question_text" => "Apakah Anda kesulitan berkonsentrasi atau mengambil keputusan?",
                "options" => ["Ya", "Tidak", "Sering"],
                "value_map" => [
                    "Ya" => 2,
                    "Sering" => 2,
                    "Tidak" => 0
                ],
                "category" => "kognitif",
                "ml_index" => 3,
                "order" => 4,
                "version" => 1,
                "created_at" => $now,
                "updated_at" => null
            ],
            [
                "field_key" => "makan_berlebihan_atau_kehilangan_nafsu_makan",
                "question_text" => "Apakah Anda makan berlebihan atau kehilangan nafsu makan?",
                "options" => ["Ya", "Tidak", "Tidak sama sekali"],
                "value_map" => [
                    "Ya" => 2,
                    "Tidak" => 1,
                    "Tidak sama sekali" => 0
                ],
                "category" => "fisik",
                "ml_index" => 4,
                "order" => 5,
                "version" => 1,
                "created_at" => $now,
                "updated_at" => null
            ],
            [
                "field_key" => "merasa_cemas_atau_gelisah",
                "question_text" => "Apakah Anda merasa cemas atau gelisah?",
                "options" => ["Ya", "Tidak", "Kadang-kadang"],
                "value_map" => [
                    "Ya" => 2,
                    "Kadang-kadang" => 1,
                    "Tidak" => 0
                ],
                "category" => "emosional",
                "ml_index" => 5,
                "order" => 6,
                "version" => 1,
                "created_at" => $now,
                "updated_at" => null
            ],
            [
                "field_key" => "perasaan_bersalah",
                "question_text" => "Apakah Anda merasa bersalah?",
                "options" => ["Ya", "Tidak", "Mungkin"],
                "value_map" => [
                    "Ya" => 2,
                    "Mungkin" => 1,
                    "Tidak" => 0
                ],
                "category" => "emosional",
                "ml_index" => 6,
                "order" => 7,
                "version" => 1,
                "created_at" => $now,
                "updated_at" => null
            ],
            [
                "field_key" => "kesulitan_membangun_ikatan_dengan_bayi",
                "question_text" => "Apakah Anda mengalami kesulitan membangun ikatan dengan bayi?",
                "options" => ["Ya", "Tidak", "Kadang-kadang"],
                "value_map" => [
                    "Ya" => 2,
                    "Kadang-kadang" => 1,
                    "Tidak" => 0
                ],
                "category" => "ikatan",
                "ml_index" => 7,
                "order" => 8,
                "version" => 1,
                "created_at" => $now,
                "updated_at" => null
            ],
            [
                "field_key" => "percobaan_bunuh_diri",
                "question_text" => "Apakah Anda pernah memiliki pikiran atau melakukan percobaan bunuh diri?",
                "options" => ["Ya", "Tidak"],
                "value_map" => [
                    "Ya" => 3,
                    "Tidak" => 0
                ],
                "category" => "kritis",
                "ml_index" => 8,
                "order" => 9,
                "version" => 1,
                "created_at" => $now,
                "updated_at" => null
            ]
        ];

        foreach ($questions as $q) {
            $collection->insertOne($q);
        }
    }
}