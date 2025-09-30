const villeparpays = {
    Algérie:["Alger","Oran","Tizi Ouzou","Bejaia"],
    France : ["Paris","Lyon","Nice","Marseille"],
};
function updatecities(){
        const pays = document.getElementById("pays").value; 
        const villeSelect= document.getElementById("ville");

        villeSelect.innerHTML='<option value=""> choisir une ville </option>';
        if (pays && villeparpays [pays]){
                villeparpays[pays].forEach(ville =>{
                    const option= document.createElement("option"); 
                    option.value = ville.toLowerCase();
                    option.textContent = ville;
                    villeSelect.appendChild(option);
        
                });
        } 
}
function redirectIfKnownCity(pays, ville) {
    const redirections = {
        "algérie": {
            "tizi ouzou": "tiziouzou.html",
            "oran": "oran.html",
            "alger": "alger.html"
        }
    };

    const paysLower = pays.toLowerCase();
    const villeLower = ville.toLowerCase();

    if (redirections[paysLower] && redirections[paysLower][villeLower]) {
        window.location.href = redirections[paysLower][villeLower];
        return true;
    }
    return false;
}

function showhotels(event) {
    event.preventDefault();
    const pays = document.getElementById("pays").value;
    const ville = document.getElementById("ville").value;

    if (redirectIfKnownCity(pays, ville)) {
        return;
    }

    const hotellist = document.getElementById("hotel-list");
    hotellist.innerHTML = '';

    if (typeof hotelparville !== "undefined" && hotelparville[ville]) {
        hotelparville[ville].forEach(hotel => {
            const hotelitem = document.createElement("li");
            hotelitem.className = "hotel-item";
            hotelitem.innerHTML = `
                <img src="${hotel.image}" alt="${hotel.name}">
                <h3><a href="${hotel.link}">${hotel.name}</a></h3>
            `;
            hotellist.appendChild(hotelitem);
        });
    } else {
        hotellist.innerHTML = '<li>Aucun hôtel trouvé pour cette ville.</li>';
    }
}


