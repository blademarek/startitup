# Intro

This is implementation of custom "Benefits" module into Nette [REMP CRM](https://github.com/remp2020/crm-skeleton).

Running this example live requires deep knowledge of REMP CRM installation and debugging/fixing after-installation errors.

The module can be found in `extensions` folder.

The integration was demonstrated in person. The code contains some basic comments for better understanding - as requested.

# Task description

Task description is provided in Slovak language only:

### Zadanie
Vytvoriť private repozitár a sharenuť nám ho, v ktorom bude https://github.com/remp2020/crm-skeleton a vytvorený Modul pre https://github.com/remp2020/ crm-skeleton, ktorý poskytne administrátorom v REMP CRM možnosť pridať „benefity“ a používateľom dovolí po kúpe prvého plateného predplatného vybrať si vo svojom profile jeden z aktívnych benefitov. ( t.j. získa po kúpe 1 kredit a môže ho minúť v admine ).

### Detajly
Private repozitár by mal obsahovať forknutý https://github.com/remp2020/crm-skeleton + spomínaný modul, vytvorený podľa inštrukcií na vytvorenie modulov https://github.com/remp2020/crm-skeleton.

Po prihlásení administrátora by sa v admine vo vrchnom menu mal nachádzať link na „Benefity“, keď naň admin klikne, mal by sa dostať na zoznam benefitov, kde bude vedieť pridať nový benefit.

Každý benefit má obsahovať
- Fotku
- Nadpis
- Kód ( napríklad „Benefit33“ )
- „odkedy“ je ho možné si zvoliť = je aktívny
- „dokedy“ je ho možné si zvoliť = je aktívny

Admin teda napríklad vytvorí 2 rôzne benefity, ktoré budú aktívne. To že či je benefit aktívny záleží od „odkedy“ a „dokedy“

Keď si čitateľ zakúpi 1. platené predplarné, dostane 1 kredit ( pre zjednodušenie zadania, predpokladajme, že predvytvorený user v CRM s e-mailom user@crm.press už dostal 1 kredit )…

Pridať do profilu usera, keď je prihlásený novú sekciu „Benefity“

V tejto sekcii zobraziť aktívne benefity s radio buttonmi, aby si mohol user zvoliť benefit.

Keď si user zvolí jeden z aktívnych benefitov, uložiť jeho voľbu, vynulovať mu kredity a priradiť mu benefit. Keď sa mu benefit priradí po uložení, refreshne sa stránka a zobrazí sa mu aj kód benefitu.
Po refreshi sa už pravdaže benefity nedajú voliť, keďže už nemá kredit = nemôže si vybrať žiadny ďalší benefit. Ten aktívny, ktorý si zvolil nejak zvýrazniť.

V administrácii v profile používateľa (http://crm.press/users/users-admin/show/2) pridať dole medzi taby ako sú aj „predplatné“, „platby“ ďalší a to „Benefity“ a zobraziť tam jeho zvolené benefity.

### Bonus:
Pridať API endpoint do REMP CRM, ktorý vráti všetky benefity daného usera. Na získanie je treba poslať POST request na daný endpoint s tokenom používateľa ako bearer-om + ID používateľa.

### Zaver
Poprosím myslieť na bezpečnosť, dodržiavať štruktúru modulov pre REMP a použiť existujúce funkcionality REMP-u, použiť primárne Nette a dodržiavať jeho coding standards a best practices, okomentovať kód ( tentokrát poprosím po lopate ideálne všade vysvetlenie )…

Tiež poprosím dodať inštrukcie ako si po inštalácii crm-skeleton-u lokálne cez docker rozbehať a otestovať daný modul, prípadne API (README inštruckie)