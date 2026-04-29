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
                "category" => "emosional",
                "is_active" => true,
                "order" => (int) 1,
                "created_at" => $now,
                "updated_at" => null
            ],
            [
                "field_key" => "mudah_marah_terhadap_bayi_dan_pasangan",
                "question_text" => "Apakah Anda mudah marah terhadap bayi atau pasangan?",
                "options" => ["Ya", "Tidak", "Kadang-kadang"],
                "category" => "emosional",
                "is_active" => true,
                "order" => (int) 2,
                "created_at" => $now,
                "updated_at" => null
            ],
            [
                "field_key" => "kesulitan_tidur_di_malam_hari",
                "question_text" => "Apakah Anda mengalami kesulitan tidur di malam hari?",
                "options" => ["Ya", "Tidak", "Dua hari atau lebih dalam seminggu"],
                "category" => "tidur",
                "is_active" => true,
                "order" => (int) 3,
                "created_at" => $now,
                "updated_at" => null
            ],
            [
                "field_key" => "kesulitan_konsentrasi_atau_mengambil_keputusan",
                "question_text" => "Apakah Anda kesulitan berkonsentrasi atau mengambil keputusan?",
                "options" => ["Ya", "Tidak", "Sering"],
                "category" => "kognitif",
                "is_active" => true,
                "order" => (int) 4,
                "created_at" => $now,
                "updated_at" => null
            ],
            [
                "field_key" => "makan_berlebihan_atau_kehilangan_nafsu_makan",
                "question_text" => "Apakah Anda makan berlebihan atau kehilangan nafsu makan?",
                "options" => ["Ya", "Tidak", "Tidak sama sekali"],
                "category" => "fisik",
                "is_active" => true,
                "order" => (int) 5,
                "created_at" => $now,
                "updated_at" => null
            ],
            [
                "field_key" => "merasa_cemas_atau_gelisah",
                "question_text" => "Apakah Anda merasa cemas atau gelisah?",
                "options" => ["Ya", "Tidak", "Kadang-kadang"],
                "category" => "emosional",
                "is_active" => true,
                "order" => (int) 6,
                "created_at" => $now,
                "updated_at" => null
            ],
            [
                "field_key" => "perasaan_bersalah",
                "question_text" => "Apakah Anda merasa bersalah?",
                "options" => ["Ya", "Tidak", "Mungkin"],
                "category" => "emosional",
                "is_active" => true,
                "order" => (int) 7,
                "created_at" => $now,
                "updated_at" => null
            ],
            [
                "field_key" => "kesulitan_membangun_ikatan_dengan_bayi",
                "question_text" => "Apakah Anda mengalami kesulitan membangun ikatan dengan bayi?",
                "options" => ["Ya", "Tidak", "Kadang-kadang"],
                "category" => "ikatan",
                "is_active" => true,
                "order" => (int) 8,
                "created_at" => $now,
                "updated_at" => null
            ],
            [
                "field_key" => "percobaan_bunuh_diri",
                "question_text" => "Apakah Anda pernah memiliki pikiran atau melakukan percobaan bunuh diri?",
                "options" => ["Ya", "Tidak"],
                "category" => "kritis",
                "is_active" => true,
                "order" => (int) 9,
                "created_at" => $now,
                "updated_at" => null
            ]
        ];

        foreach ($questions as $q) {
            if (!is_int($q['order'])) {
                dd('order bukan int', $q);
            }

            if (!($q['created_at'] instanceof \MongoDB\BSON\UTCDateTime)) {
                dd('created_at bukan BSON date', $q);
            }

            $collection->insertOne($q);
        }
    }
}