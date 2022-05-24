<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Test User 1
        $tU1 = new User;
        $tU1->name = 'Maria123';
        $tU1->degree = 'Kommunikation, Wissen, Medien';
        $tU1->degree_description = 'KWM ist ein Bachelorstudiengang an der FH Hagenberg.
        KWM ist ein interdisziplinäres Studium, dass UI/UX Design, Webentwicklung,
        E-LEarning und Kommunikation vereint.';
        $tU1->email = 'tU1@email.at';
        $tU1->telephone = '0660/00112233';
        $tU1->password = bcrypt("secret1");
        $tU1->is_coach = false;
        $tU1->save();

        //Test User 2
        $tU2 = new User;
        $tU2->name = 'Jorle';
        $tU2->degree = 'Interactive Media';
        $tU2->degree_description = 'IM ist ein Masterstudiengang an der FH Hagenberg.
        IM hat verschiedene Schwerpunkte wie den Datenjournalismus, Interaktive Medien, Spieleentwicklung, Online Medien.';
        $tU2->email = 'tU2@email.at';
        $tU2->telephone = '0660/99887766';
        $tU2->password = bcrypt("secret2");
        $tU2->is_coach = true;
        $tU2->save();
    }
}
