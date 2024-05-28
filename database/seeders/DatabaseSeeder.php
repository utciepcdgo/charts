<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        \App\Models\User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('secret')
        ]);

        $ine_users = array(
            array(
                "name" => "Guadalupe Taddei Zavala",
                "email" => "ine.ce.gtz@iepcdurango.mx",
                "password" => bcrypt("pe2DCQ6U")),
            array(
                "name" => "Arturo Castillo Loza",
                "email" => "ine.ce.acl@iepcdurango.mx",
                "password" => bcrypt("B8Zv9LDx")),
            array(
                "name" => "Beatriz Claudia Zavala Pérez",
                "email" => "ine.ce.bczp@iepcdurango.mx",
                "password" => bcrypt("e48AsXx3")),
            array(
                "name" => "Carla Astrid Humphrey Jordan",
                "email" => "ine.ce.cahj@iepcdurango.mx",
                "password" => bcrypt("9FmvV2a3")),
            array(
                "name" => "Dania Paola Ravel Cuevas",
                "email" => "ine.ce.dprc@iepcdurango.mx",
                "password" => bcrypt("p8DTCZRB")),
            array(
                "name" => "Jaime Rivera Velázquez",
                "email" => "ine.ce.jrv@iepcdurango.mx",
                "password" => bcrypt("38a8SzuT")),
            array(
                "name" => "Jorge Montaño Ventura",
                "email" => "ine.ce.jmv@iepcdurango.mx",
                "password" => bcrypt("pL6a72qS")),
            array(
                "name" => "José Martín Fernando Faz Mora",
                "email" => "ine.ce.jmffm@iepcdurango.mx",
                "password" => bcrypt("S3bstwSb")),
            array(
                "name" => "Norma Irene de la Cruz Magaña",
                "email" => "ine.ce.nicm@iepcdurango.mx",
                "password" => bcrypt("sGJ68UU6")),
            array(
                "name" => "Rita Bell López Vences",
                "email" => "ine.ce.rblv@iepcdurango.mx",
                "password" => bcrypt("mTRjU4BB")),
            array(
                "name" => "Uuc-kib Espadas Ancona",
                "email" => "ine.ce.uea@iepcdurango.mx",
                "password" => bcrypt("BT5CZFf6")),
            array(
                "name" => "Claudia Edith Suárez Ojeda",
                "email" => "ine.ce.ceso@iepcdurango.mx",
                "password" => bcrypt("rLxuu3NN")),
            array(
                "name" => "UTVOPL 1",
                "email" => "ine.ut.vopl01@iepcdurango.mx",
                "password" => bcrypt("wty85E8d")),
            array(
                "name" => "UTVOPL 2",
                "email" => "ine.ut.vopl02@iepcdurango.mx",
                "password" => bcrypt("DF6CtzzF")),
            array(
                "name" => "UTVOPL 3",
                "email" => "ine.ut.vopl03@iepcdurango.mx",
                "password" => bcrypt("H9F6pa7t")),
            array(
                "name" => "UTVOPL 4",
                "email" => "ine.ut.vopl04@iepcdurango.mx",
                "password" => bcrypt("2CTbrX4y")),
            array(
                "name" => "UTVOPL 5",
                "email" => "ine.ut.vopl05@iepcdurango.mx",
                "password" => bcrypt("zCxPwJ3W"))
        );

        foreach ($ine_users as $ine_user) {
           User::factory()->create($ine_user);
        }
    }
}
