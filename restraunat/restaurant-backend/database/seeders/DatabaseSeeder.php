<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;
use App\Models\News;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Пицца',    'slug' => 'pizza'],
            ['name' => 'Бургеры',  'slug' => 'burgers'],
            ['name' => 'Роллы',    'slug' => 'rolls'],
            ['name' => 'Напитки',  'slug' => 'drinks'],
            ['name' => 'Десерты',  'slug' => 'desserts'],
        ];

        foreach ($categories as $cat) {
            Category::create($cat);
        }

        $products = [
            ['name'=>'Маргарита',    'price'=>590,  'category_id'=>1, 'description'=>'Классическая пицца с томатами и моцареллой', 'weight'=>450, 'calories'=>900],
            ['name'=>'Пепперони',    'price'=>690,  'category_id'=>1, 'description'=>'С острой колбасой и сыром', 'weight'=>500, 'calories'=>1100],
            ['name'=>'4 сыра',       'price'=>750,  'category_id'=>1, 'description'=>'Четыре вида итальянского сыра', 'weight'=>480, 'calories'=>1050],
            ['name'=>'Классик',      'price'=>490,  'category_id'=>2, 'description'=>'Говяжья котлета, салат, томат', 'weight'=>300, 'calories'=>700],
            ['name'=>'Чизбургер',    'price'=>450,  'category_id'=>2, 'description'=>'С двойным сыром', 'weight'=>280, 'calories'=>650],
            ['name'=>'Филадельфия',  'price'=>420,  'category_id'=>3, 'description'=>'Лосось, сливочный сыр', 'weight'=>280, 'calories'=>480],
            ['name'=>'Дракон',       'price'=>390,  'category_id'=>3, 'description'=>'С угрём и авокадо', 'weight'=>260, 'calories'=>520],
            ['name'=>'Кола',         'price'=>120,  'category_id'=>4, 'description'=>'0.5 л', 'weight'=>500, 'calories'=>210],
            ['name'=>'Тирамису',     'price'=>290,  'category_id'=>5, 'description'=>'Классический итальянский десерт', 'weight'=>180, 'calories'=>420],
        ];

        foreach ($products as $p) {
            Product::create($p);
        }

        News::insert([
            ['title'=>'Открытие нового зала', 'body'=>'Рады сообщить об открытии нового зала в центре города!', 'published_at'=>now()],
            ['title'=>'Акция: 2 пиццы по цене 1', 'body'=>'Каждую среду — скидка 50% на вторую пиццу!', 'published_at'=>now()],
        ]);
    }
}
