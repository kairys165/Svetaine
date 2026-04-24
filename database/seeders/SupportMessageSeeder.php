<?php

namespace Database\Seeders;

use App\Models\SupportMessage;
use App\Models\User;
use Illuminate\Database\Seeder;

class SupportMessageSeeder extends Seeder
{
    public function run(): void
    {
        SupportMessage::query()->delete();

        $users = User::where('is_admin', false)->get();
        $conversations = [
            [
                'subject' => 'Pristatymas',
                'message' => 'Sveiki, noriu pasitikslinti kada bus išsiųstas mano užsakymas.',
                'status' => 'closed',
                'admin_reply' => 'Sveiki! Jūsų užsakymas Nr. #FIT-2026-0042 buvo išsiųstas vakar, sekimo kodas: LP123456789LT. Galite sekti siuntą Lietuvos pašto svetainėje. Pristatymas turėtų įvykti per 1–2 darbo dienas. Jei kiltų klausimų — drąsiai rašykite!',
            ],
            [
                'subject' => 'Užsakymas',
                'message' => 'Ar galima pakeisti pristatymo adresą po apmokėjimo?',
                'status' => 'in_progress',
                'admin_reply' => 'Sveiki! Taip, adresą galime pakeisti, jei siunta dar nebuvo perduota kurjeriui. Prašome atsiųsti naują adresą atsakydami į šią žinutę, ir mes atnaujinsime informaciją. Paprastai siuntas perduodame per 24 val. nuo apmokėjimo.',
            ],
            [
                'subject' => 'Grąžinimas',
                'message' => 'Gavau ne tą skonį, kokį užsakiau. Kaip atlikti keitimą?',
                'status' => 'in_progress',
                'admin_reply' => 'Labai atsiprašome dėl klaidos! Prašome atsiųsti užsakymo numerį ir nuotrauką gauto produkto etiketės. Mes išsiųsime teisingą produktą per 1–2 darbo dienas, o netinkamą galėsite pasilikti arba grąžinti nemokamai. Dar kartą atsiprašome už nepatogumą.',
            ],
            [
                'subject' => 'Produktas',
                'message' => 'Ar šis produktas tinka vartoti prieš treniruotę?',
                'status' => 'closed',
                'admin_reply' => 'Sveiki! Taip, Pre-Workout C4 yra specialiai sukurtas vartoti 20–30 min. prieš treniruotę. Rekomenduojama dozė — 1 matavimo šaukštelis su 200 ml vandens. Nerekomenduojame vartoti vėlai vakare, nes sudėtyje yra kofeino (150 mg porcijoje). Jei turite jautrumo kofeinui, pradėkite nuo pusės dozės.',
            ],
            [
                'subject' => 'Kita',
                'message' => 'Kiek laiko galioja nuolaidos kodas WELCOME10?',
                'status' => 'closed',
                'admin_reply' => 'Sveiki! Nuolaidos kodas WELCOME10 suteikia 10% nuolaidą pirminiam užsakymui ir galioja 30 dienų nuo registracijos datos. Kodą galite įvesti krepšelio puslapyje laukelyje „Nuolaidos kodas". Minimali užsakymo suma — 15 €.',
            ],
            [
                'subject' => 'Pristatymas',
                'message' => 'Ar pristatote savaitgaliais?',
                'status' => 'resolved',
                'admin_reply' => 'Sveiki! Savaitgaliais siuntų nesiunčiame, tačiau jei užsakymas pateiktas penktadienį iki 15:00, jis bus išsiųstas tą pačią dieną ir paštomatas gali pristatyti šeštadienį. Užsakymai po 15:00 penktadienį išsiunčiami pirmadienį.',
            ],
            [
                'subject' => 'Produktas',
                'message' => 'Ar turite veganišką baltymų miltelių variantą?',
                'status' => 'resolved',
                'admin_reply' => 'Sveiki! Šiuo metu turime Plant Protein mišinį (žirnių + ryžių baltymai), kuris yra 100% veganiškas. Rasite jį kategorijoje „Baltymai". Kiekviena porcija turi 24g baltymų. Jei ieškote konkretaus skonio — rašykite, ir padėsime išsirinkti!',
            ],
            [
                'subject' => 'Grąžinimas',
                'message' => 'Noriu grąžinti neatidarytą produktą. Kokia tvarka?',
                'status' => 'closed',
                'admin_reply' => 'Sveiki! Neatidarytus produktus galite grąžinti per 14 dienų nuo gavimo. Grąžinimo žingsniai: 1) Atsiųskite mums užsakymo numerį; 2) Mes atsiųsime grąžinimo etiketę el. paštu; 3) Pristatykite siuntą į artimiausią paštomatą. Pinigai grąžinami per 3–5 darbo dienas po siuntos gavimo.',
            ],
            [
                'subject' => 'Užsakymas',
                'message' => 'Užsakymas pažymėtas kaip apmokėtas, bet negavau patvirtinimo el. paštu.',
                'status' => 'resolved',
                'admin_reply' => 'Sveiki! Patikrinome — jūsų užsakymas Nr. #FIT-2026-0038 sėkmingai apmokėtas ir ruošiamas siuntimui. Patvirtinimo laiškas buvo išsiųstas, tačiau gali būti patekęs į „Spam" ar „Šlamštas" aplanką. Prašome patikrinti. Taip pat galite matyti savo užsakymų istoriją prisijungę prie paskyros skiltyje „Mano užsakymai".',
            ],
            [
                'subject' => 'Pristatymas',
                'message' => 'Mano siunta jau 5 dienos keliauja. Ar viskas gerai?',
                'status' => 'in_progress',
                'admin_reply' => 'Sveiki! Atsiprašome dėl vėlavimo. Patikrinsime jūsų siuntos būseną su kurjerio tarnyba ir informuosime per 24 val. Jei siunta nebus pristatyta per 7 darbo dienas — išsiųsime naują nemokamai. Prašome atsiųsti savo užsakymo numerį, kad galėtume greičiau patikrinti.',
            ],
            [
                'subject' => 'Kita',
                'message' => 'Ar galite rekomenduoti papildus pradedančiam sportininkui?',
                'status' => 'closed',
                'admin_reply' => 'Sveiki! Pradedančiajam rekomenduojame: 1) Išrūgų baltymus (Whey Protein) — papildyti baltymų poreikį po treniruočių; 2) Kreatino monohidratą — padidinti jėgą ir ištvermę; 3) Multivitaminus — užtikrinti mikroelementų balansą. Taip pat rekomenduojame pasinaudoti mūsų kalorijų skaičiuokle ir mitybos planuotoju — rasite skiltyje „Mityba". Sėkmės treniruotėse!',
            ],
            [
                'subject' => 'Užsakymas',
                'message' => 'Ar galiu pridėti papildomą prekę prie jau apmokėto užsakymo?',
                'status' => 'closed',
                'admin_reply' => 'Sveiki! Deja, prie apmokėto užsakymo papildomų prekių pridėti negalime, nes sistema automatiškai pradeda paruošimą siuntimui. Tačiau galite pateikti naują užsakymą — jei abiejų užsakymų bendra suma viršija 50 €, pristatymas bus nemokamas. Jei užsakymas dar neišsiųstas, galime pabandyti sujungti — parašykite abu užsakymų numerius.',
            ],
        ];

        foreach ($conversations as $i => $conv) {
            $user = $users->isNotEmpty() ? $users->random() : null;
            $createdAt = now()->subDays(rand(1, 30))->subMinutes(rand(0, 600));
            $hasReply = in_array($conv['status'], ['resolved', 'closed', 'in_progress'], true);

            SupportMessage::create([
                'user_id' => $user?->id,
                'name' => $user?->name ?? 'Lankytojas ' . ($i + 1),
                'email' => $user?->email ?? "lankytojas" . ($i + 1) . "@example.com",
                'subject' => $conv['subject'],
                'message' => $conv['message'],
                'status' => $conv['status'],
                'admin_reply' => $hasReply ? $conv['admin_reply'] : null,
                'replied_at' => $hasReply ? $createdAt->copy()->addHours(rand(2, 8)) : null,
                'created_at' => $createdAt,
                'updated_at' => now(),
            ]);
        }
    }
}
