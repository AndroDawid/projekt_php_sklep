function zegarek() {
    let teraz = new Date();
    let godziny = teraz.getHours();
    let minuty = teraz.getMinutes();
    let sekundy = teraz.getSeconds();
    let dzien = teraz.getDate();
    let miesiac = teraz.getMonth() + 1;
    let rok = teraz.getFullYear();

    if (godziny < 10) godziny = "0" + godziny;
    if (minuty < 10) minuty = "0" + minuty;
    if (sekundy < 10) sekundy = "0" + sekundy;
    if (dzien < 10) dzien = "0" + dzien;
    if (miesiac < 10) miesiac = "0" + miesiac;

    let tekst = dzien + "-" + miesiac + "-" + rok + " " + godziny + ":" + minuty + ":" + sekundy;
    document.getElementById("clock").textContent = tekst;
}

setInterval(zegarek, 1);
