<?php

namespace Database\Seeders;

use App\Models\Subject;
use Illuminate\Database\Seeder;

class SubjectsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Test Subject 1
        $tSub1 = new Subject;
        $tSub1->title = 'Networked/Distributed Systems';
        $tSub1->description = '';
        $tSub1->save();

        //Test Subject 1
        $tSub1 = new Subject;
        $tSub1->title = 'Human-Computer Interaction';
        $tSub1->description = '';
        $tSub1->save();

        //Test Subject 1
        $tSub1 = new Subject;
        $tSub1->title = 'E-Learning: Konzepte, Umsetzung und Qualitätssicherung';
        $tSub1->description = '';
        $tSub1->save();

        //Test Subject 1
        $tSub1 = new Subject;
        $tSub1->title = 'Web-Entwicklung: Architekturen und Frameworks';
        $tSub1->description = 'Im Mittelpunkt stehen: Architekturmuster für umfangreiche Client/Server Anwendungen, Anbieten
        von Services mittels Webstandards (z.B. REST, Webservices), AJAX,
        Aktuelle Trends und Frameworks in der Frontendentwicklung (z.B. Angular),
        Single Page Web-Anwendungen; Vermittlung eines modernen Webentwicklungszyklus
        inklusiver dazugehöriger Werkzeuge (Einsatz von Versionsverwaltung,
        Testframeworks, Build-Tools).';
        $tSub1->save();

        //Test Subject 1
        $tSub1 = new Subject;
        $tSub1->title = 'Content Strategie ';
        $tSub1->description = '';
        $tSub1->save();
    }
}
