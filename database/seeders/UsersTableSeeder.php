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

        //Test User 1
        $tU3 = new User;
        $tU3->name = 'Maria1';
        $tU3->degree = 'Kommunikation, Wissen, Medien';
        $tU3->degree_description = 'KWM ist ein Bachelorstudiengang an der FH Hagenberg.
        KWM ist ein interdisziplinäres Studium, dass UI/UX Design, Webentwicklung,
        E-LEarning und Kommunikation vereint.';
        $tU3->email = 'tU3@email.at';
        $tU3->telephone = '0660/001122332';
        $tU3->password = bcrypt("secret3");
        $tU3->is_coach = false;
        $tU3->save();

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

        //Test User 2
        $tU4 = new User;
        $tU4->name = 'Jorle2';
        $tU4->degree = 'Interactive Media';
        $tU4->degree_description = 'IM ist ein Masterstudiengang an der FH Hagenberg.
        IM hat verschiedene Schwerpunkte wie den Datenjournalismus, Interaktive Medien, Spieleentwicklung, Online Medien.';
        $tU4->email = 'tU4@email.at';
        $tU4->telephone = '0660/998877663';
        $tU4->password = bcrypt("secret4");
        $tU4->is_coach = true;
        $tU4->save();
    }
}
