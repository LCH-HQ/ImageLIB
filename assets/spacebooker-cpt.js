var select = document.getElementById("beschikbaarheid_dropdown");

function verbergInputVelden() {
    if(select.value=="beperkt_beschikbaar"){
       document.getElementById("begintijd").style.display="block";
    }else{
       document.getElementById("begintijd").style.display="none";
    }

}
