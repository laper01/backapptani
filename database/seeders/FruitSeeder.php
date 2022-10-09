<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\fruit;

class FruitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $fruit = new fruit();
        $fruit->name ="Manggis";
        $fruit->save();

        $fruit = new fruit();
        $fruit->name ="Rambutan";
        $fruit->save();

        $fruit = new fruit();
        $fruit->name ="Durian";
        $fruit->save();
    }
}
