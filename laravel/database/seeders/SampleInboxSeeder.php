<?php

namespace Database\Seeders;

use App\Models\Inquiry;
use App\Support\Mill;
use Illuminate\Database\Seeder;

class SampleInboxSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            ['received_on' => '2026-09-08', 'company' => 'Nordic Home Textiles', 'interest' => 'Finished fabric', 'status' => 'new', 'message' => 'Request for finished fabric program details.', 'image' => 'assets/images/products/product-3.jpg'],
            ['received_on' => '2026-09-06', 'company' => 'Delta Apparel Co.', 'interest' => 'Yarn', 'status' => 'in_progress', 'message' => 'Yarn count and lead-time question.', 'image' => 'assets/images/gallery/gallery-7.jpg'],
            ['received_on' => '2026-09-02', 'company' => 'Eastern Traders', 'interest' => 'Greige woven', 'status' => 'closed', 'message' => 'Greige woven inquiry, closed after reply.', 'image' => 'assets/images/products/product-1.jpg'],
        ];

        foreach ($rows as $row) {
            $image = Mill::copyPublicImage($row['image']);
            Inquiry::query()->firstOrCreate(
                [
                    'company' => $row['company'],
                    'received_on' => $row['received_on'],
                    'interest' => $row['interest'],
                ],
                [
                    'status' => $row['status'],
                    'message' => $row['message'],
                    'image' => $image,
                ]
            );
        }
    }
}
